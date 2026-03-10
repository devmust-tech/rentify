<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\InvoiceStatus;
use App\Enums\NotificationType;
use App\Mail\InvoiceGenerated;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Unit;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');

        $invoices = Invoice::whereIn('lease_id', $leaseIds)
            ->when($request->search, fn($q) => $q
                ->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('lease.tenant.user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with(['lease.tenant.user', 'lease.unit.property', 'payments'])
            ->orderByDesc('due_date')
            ->paginate(15)->withQueryString();

        return view('agent.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leases = Lease::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->with(['tenant.user', 'unit.property'])
            ->get();

        return view('agent.invoices.create', compact('leases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $org = app('currentOrganization');
        $year = now()->year;

        // Generate org-scoped sequential invoice number: INV-YYYY-NNNN
        $last = Invoice::where('organization_id', $org->id)
            ->whereYear('created_at', $year)
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;
        $invoiceNumber = 'INV-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            ...$validated,
            'invoice_number' => $invoiceNumber,
            'status' => InvoiceStatus::PENDING,
        ]);

        $invoice->load('lease.tenant.user', 'lease.unit.property');
        Mail::to($invoice->lease->tenant->user->email)->queue(new InvoiceGenerated($invoice));

        // In-app notification for tenant (sendEmail=false to avoid double email)
        app(NotificationService::class)->notify(
            user: $invoice->lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'New Invoice',
            message: 'New invoice of KSh ' . number_format((float) $invoice->amount, 2) . ' due on ' . $invoice->due_date->format('d M Y') . '.',
            sendEmail: false,
        );

        app(ActivityLogger::class)->log(
            'invoice.created',
            "Invoice {$invoiceNumber} created for {$invoice->lease->tenant->user->name} — KSh " . number_format((float) $invoice->amount, 2),
            $invoice,
        );

        return redirect()->route('agent.invoices.index')->with('success', 'Invoice created.');
    }

    public function show(string $org, Invoice $invoice)
    {
        $invoice->load(['lease.tenant.user', 'lease.unit.property', 'payments']);
        return view('agent.invoices.show', compact('invoice'));
    }

    public function export(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');

        $invoices = Invoice::whereIn('lease_id', $leaseIds)
            ->when($request->search, fn($q) => $q
                ->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('lease.tenant.user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with(['lease.tenant.user', 'lease.unit.property', 'payments'])
            ->orderByDesc('due_date')
            ->get();

        $filename = 'invoices-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($invoices) {
            $fh = fopen('php://output', 'w');
            fputcsv($fh, ['Invoice #', 'Tenant', 'Unit', 'Property', 'Amount', 'Total Paid', 'Balance', 'Due Date', 'Status']);
            foreach ($invoices as $inv) {
                fputcsv($fh, [
                    $inv->invoice_number ?? '',
                    $inv->lease->tenant->user->name,
                    $inv->lease->unit->unit_number,
                    $inv->lease->unit->property->name,
                    number_format((float) $inv->amount, 2),
                    number_format((float) $inv->payments->sum('amount'), 2),
                    number_format(max(0, (float) $inv->amount - (float) $inv->payments->sum('amount')), 2),
                    $inv->due_date->format('d/m/Y'),
                    $inv->status->value,
                ]);
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function download(string $org, Invoice $invoice)
    {
        $invoice->load(['lease.tenant.user', 'lease.unit.property', 'payments']);
        $org = app('currentOrganization');

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'org'))
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-' . strtolower(substr($invoice->id, -8)) . '.pdf';

        return $pdf->download($filename);
    }
}
