<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\NotificationType;
use App\Enums\PaymentStatus;
use App\Mail\PaymentReceived;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Unit;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');
        $invoiceIds = Invoice::whereIn('lease_id', $leaseIds)->pluck('id');

        $payments = Payment::whereIn('invoice_id', $invoiceIds)
            ->when($request->search, fn($q) => $q
                ->whereHas('invoice', fn($i) => $i->where('invoice_number', 'like', '%' . $request->search . '%'))
                ->orWhereHas('invoice.lease.tenant.user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->method, fn($q) => $q->where('method', $request->method))
            ->with(['invoice.lease.tenant.user', 'invoice.lease.unit.property'])
            ->orderByDesc('paid_at')
            ->paginate(15)->withQueryString();

        return view('agent.payments.index', compact('payments'));
    }

    public function export(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');
        $invoiceIds = Invoice::whereIn('lease_id', $leaseIds)->pluck('id');

        $payments = Payment::whereIn('invoice_id', $invoiceIds)
            ->when($request->search, fn($q) => $q
                ->whereHas('invoice', fn($i) => $i->where('invoice_number', 'like', '%' . $request->search . '%'))
                ->orWhereHas('invoice.lease.tenant.user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->method, fn($q) => $q->where('method', $request->method))
            ->with(['invoice.lease.tenant.user', 'invoice.lease.unit.property'])
            ->orderByDesc('paid_at')
            ->get();

        $filename = 'payments-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $fh = fopen('php://output', 'w');
            fputcsv($fh, ['Invoice #', 'Tenant', 'Unit', 'Amount', 'Commission', 'Method', 'Reference', 'M-Pesa Receipt', 'Status', 'Paid At']);
            foreach ($payments as $p) {
                fputcsv($fh, [
                    $p->invoice->invoice_number ?? '',
                    $p->invoice->lease->tenant->user->name,
                    $p->invoice->lease->unit->unit_number,
                    number_format((float) $p->amount, 2),
                    $p->commission_amount ? number_format((float) $p->commission_amount, 2) : '',
                    $p->method->value,
                    $p->reference ?? '',
                    $p->mpesa_receipt ?? '',
                    $p->status->value,
                    $p->paid_at?->format('d/m/Y H:i') ?? '',
                ]);
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');

        $invoices = Invoice::whereIn('lease_id', $leaseIds)
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])
            ->with(['lease.tenant.user', 'lease.unit.property'])
            ->get();

        return view('agent.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'reference' => 'nullable|string',
            'mpesa_receipt' => 'nullable|string',
        ]);

        $org              = app('currentOrganization');
        $commissionRate   = (float) ($org->settings['commission_rate'] ?? 0);
        $commissionAmount = $commissionRate > 0
            ? round((float) $validated['amount'] * $commissionRate / 100, 2)
            : null;

        $payment = Payment::create([
            ...$validated,
            'commission_amount' => $commissionAmount,
            'status' => PaymentStatus::COMPLETED,
            'paid_at' => now(),
        ]);

        // Auto-update invoice status based on total payments
        Invoice::find($validated['invoice_id'])->updateStatus();

        $payment->load('invoice.lease.tenant.user', 'invoice.lease.unit.property');
        Mail::to($payment->invoice->lease->tenant->user->email)->queue(new PaymentReceived($payment));

        // In-app notification for tenant (sendEmail=false to avoid double email)
        app(NotificationService::class)->notify(
            user: $payment->invoice->lease->tenant->user,
            type: NotificationType::PAYMENT_REMINDER,
            subject: 'Payment Received',
            message: 'Payment of KSh ' . number_format($payment->amount, 2) . ' received for invoice #' . $payment->invoice->id . '.',
            sendEmail: false,
        );

        app(ActivityLogger::class)->log(
            'payment.recorded',
            "Payment of KSh " . number_format((float) $payment->amount, 2) . " recorded via {$payment->method->value} for " . $payment->invoice->lease->tenant->user->name,
            $payment,
        );

        return redirect()->route('agent.payments.index')->with('success', 'Payment recorded.');
    }

    public function show(string $org, Payment $payment)
    {
        $payment->load(['invoice.lease.tenant.user', 'invoice.lease.unit.property']);
        return view('agent.payments.show', compact('payment'));
    }
}
