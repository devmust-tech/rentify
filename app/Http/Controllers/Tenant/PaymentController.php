<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $payments = Payment::whereHas('invoice.lease', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id);
        })
        ->with('invoice')
        ->orderBy('paid_at', 'desc')
        ->paginate(20);

        return view('tenant.payments.index', compact('payments'));
    }

    public function initiate(Request $request, Invoice $invoice)
    {
        // Ensure the invoice belongs to this tenant
        $tenant = $request->user()->tenant;
        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        // M-Pesa payment initiation logic would go here
        return redirect()->route('tenant.invoices.show', $invoice)
            ->with('info', 'Payment initiation coming soon');
    }
}
