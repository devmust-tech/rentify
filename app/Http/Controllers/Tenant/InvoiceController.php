<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $invoices = Invoice::whereHas('lease', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id);
        })
        ->with('lease.unit.property')
        ->orderBy('due_date', 'desc')
        ->paginate(20);

        return view('tenant.invoices.index', compact('invoices'));
    }

    public function show(Request $request, string $org, Invoice $invoice)
    {
        // Ensure the invoice belongs to this tenant
        $tenant = $request->user()->tenant;
        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->load(['lease.unit.property', 'payments']);
        return view('tenant.invoices.show', compact('invoice'));
    }

    public function download(Request $request, string $org, Invoice $invoice)
    {
        $tenant = $request->user()->tenant;
        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->load(['lease.tenant.user', 'lease.unit.property', 'payments']);
        $org = app('currentOrganization');

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'org'))
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-' . strtolower(substr($invoice->id, -8)) . '.pdf';

        return $pdf->download($filename);
    }
}
