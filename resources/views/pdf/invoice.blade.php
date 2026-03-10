<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .page { padding: 48px; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .org-name { font-size: 22px; font-weight: 700; color: #111827; }
        .org-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .invoice-label { text-align: right; }
        .invoice-label .title { font-size: 28px; font-weight: 700; color: #312e81; letter-spacing: -0.5px; }
        .invoice-label .number { font-size: 12px; color: #6b7280; margin-top: 4px; }

        /* Divider */
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }

        /* Meta row */
        .meta { display: flex; justify-content: space-between; margin-bottom: 32px; }
        .meta-col {}
        .meta-col .label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 6px; }
        .meta-col .value { font-size: 14px; font-weight: 600; color: #111827; }
        .meta-col .value-sm { font-size: 13px; color: #374151; margin-top: 2px; }

        /* Table */
        .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table thead tr { background-color: #f3f4f6; }
        .table th { padding: 10px 14px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; text-align: left; }
        .table th.right { text-align: right; }
        .table td { padding: 12px 14px; border-bottom: 1px solid #f3f4f6; font-size: 13px; color: #374151; }
        .table td.right { text-align: right; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* Totals */
        .totals { width: 280px; margin-left: auto; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { padding: 7px 0; font-size: 13px; color: #374151; }
        .totals td.label { color: #6b7280; }
        .totals td.amount { text-align: right; font-weight: 500; }
        .totals .total-row td { border-top: 2px solid #e5e7eb; padding-top: 12px; font-size: 15px; font-weight: 700; color: #111827; }
        .totals .total-row td.amount { color: #312e81; }
        .balance-row td { color: #dc2626; font-weight: 700; }
        .balance-row td.amount { color: #dc2626; }
        .paid-row td { color: #059669; }

        /* Status badge */
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-overdue { background-color: #fee2e2; color: #991b1b; }
        .status-partial { background-color: #dbeafe; color: #1e40af; }

        /* Payments section */
        .section-title { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin-bottom: 12px; }

        /* Footer */
        .footer { margin-top: 48px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="org-name">{{ $org->name }}</div>
            <div class="org-sub">{{ $org->slug }}.{{ config('app.domain') }}</div>
        </div>
        <div class="invoice-label">
            <div class="title">INVOICE</div>
            <div class="number">{{ strtoupper(substr($invoice->id, -8)) }}</div>
        </div>
    </div>

    <hr class="divider">

    {{-- Bill To / Dates --}}
    <div class="meta">
        <div class="meta-col">
            <div class="label">Bill To</div>
            <div class="value">{{ $invoice->lease->tenant->user->name }}</div>
            <div class="value-sm">{{ $invoice->lease->tenant->user->email }}</div>
            @if($invoice->lease->tenant->user->phone)
            <div class="value-sm">{{ $invoice->lease->tenant->user->phone }}</div>
            @endif
        </div>
        <div class="meta-col">
            <div class="label">Property</div>
            <div class="value">{{ $invoice->lease->unit->property->name }}</div>
            <div class="value-sm">Unit {{ $invoice->lease->unit->unit_number }}</div>
            <div class="value-sm">{{ $invoice->lease->unit->property->address }}</div>
        </div>
        <div class="meta-col" style="text-align:right">
            <div class="label">Issue Date</div>
            <div class="value">{{ $invoice->created_at->format('d M Y') }}</div>
            <div style="margin-top:12px">
                <div class="label">Due Date</div>
                <div class="value">{{ $invoice->due_date->format('d M Y') }}</div>
            </div>
            <div style="margin-top:12px">
                <span class="status-badge
                    @if($invoice->status->value === 'paid') status-paid
                    @elseif($invoice->status->value === 'overdue') status-overdue
                    @elseif($invoice->status->value === 'partially_paid') status-partial
                    @else status-pending @endif">
                    {{ str_replace('_', ' ', $invoice->status->value) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Line items --}}
    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $invoice->description ?: 'Monthly Rent' }}
                    <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                        Period: {{ $invoice->due_date->format('M Y') }}
                    </div>
                </td>
                <td class="right">KSh {{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <table>
            <tr>
                <td class="label">Subtotal</td>
                <td class="amount">KSh {{ number_format($invoice->amount, 2) }}</td>
            </tr>
            @if($invoice->total_paid > 0)
            <tr class="paid-row">
                <td class="label">Payments Received</td>
                <td class="amount">- KSh {{ number_format($invoice->total_paid, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="label">Balance Due</td>
                <td class="amount">KSh {{ number_format($invoice->balance, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Payments history --}}
    @if($invoice->payments->where('status.value', 'completed')->count() > 0)
    <div style="margin-top:32px">
        <div class="section-title">Payment History</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments->where('status.value', 'completed') as $payment)
                <tr>
                    <td>{{ $payment->paid_at?->format('d M Y H:i') ?? '—' }}</td>
                    <td>{{ strtoupper($payment->method->value) }}</td>
                    <td>{{ $payment->mpesa_receipt ?? $payment->reference ?? '—' }}</td>
                    <td class="right">KSh {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        Generated by Rentify &bull; {{ now()->format('d M Y H:i') }} &bull; {{ $org->name }}
    </div>

</div>
</body>
</html>
