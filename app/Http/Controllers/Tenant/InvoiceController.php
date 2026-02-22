<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
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

    public function show(Request $request, Invoice $invoice)
    {
        // Ensure the invoice belongs to this tenant
        $tenant = $request->user()->tenant;
        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->load(['lease.unit.property', 'payments']);
        return view('tenant.invoices.show', compact('invoice'));
    }
}
