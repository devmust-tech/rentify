<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\NotificationType;
use App\Enums\PaymentStatus;
use App\Mail\PaymentReceived;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    private function getLandlordInvoiceIds(Request $request)
    {
        $propertyIds = Property::where('landlord_id', $request->user()->landlord->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');
        return Invoice::whereIn('lease_id', $leaseIds)->pluck('id');
    }

    public function index(Request $request)
    {
        $invoiceIds = $this->getLandlordInvoiceIds($request);

        $payments = Payment::whereIn('invoice_id', $invoiceIds)
            ->with(['invoice.lease.tenant.user', 'invoice.lease.unit.property'])
            ->orderByDesc('paid_at')
            ->paginate(15);

        return view('landlord.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoiceIds = $this->getLandlordInvoiceIds($request);

        $invoices = Invoice::whereIn('id', $invoiceIds)
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])
            ->with(['lease.tenant.user', 'lease.unit.property'])
            ->get();

        return view('landlord.payments.create', compact('invoices'));
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

        // Verify invoice belongs to landlord's property
        $invoiceIds = $this->getLandlordInvoiceIds($request);
        if (!$invoiceIds->contains($validated['invoice_id'])) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $payment = Payment::create([
            ...$validated,
            'status' => PaymentStatus::COMPLETED,
            'paid_at' => now(),
        ]);

        // Auto-update invoice status based on total payments
        $payment->invoice->updateStatus();

        $payment->load('invoice.lease.tenant.user', 'invoice.lease.unit.property');
        Mail::to($payment->invoice->lease->tenant->user->email)->queue(new PaymentReceived($payment));

        app(NotificationService::class)->notify(
            user: $payment->invoice->lease->tenant->user,
            type: NotificationType::PAYMENT_REMINDER,
            subject: 'Payment Received',
            message: 'Payment of KSh ' . number_format($payment->amount, 2) . ' received for invoice #' . $payment->invoice->id . '.',
            sendEmail: false,
        );

        return redirect()->route('landlord.payments.index')->with('success', 'Payment recorded.');
    }

    public function show(Request $request, Payment $payment)
    {
        $invoiceIds = $this->getLandlordInvoiceIds($request);
        if (!$invoiceIds->contains($payment->invoice_id)) {
            abort(403, 'Unauthorized access to this payment.');
        }

        $payment->load(['invoice.lease.tenant.user', 'invoice.lease.unit.property']);
        return view('landlord.payments.show', compact('payment'));
    }
}
