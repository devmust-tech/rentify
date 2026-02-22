@extends('emails.layout')

@section('title', 'Invoice Generated - Rentify')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #111827; font-size: 22px; font-weight: 600;">
        Rent Invoice
    </h2>

    <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Dear {{ $tenantName }},
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Your rent invoice for <strong>{{ $month }}</strong> has been generated. Please find the details below:
    </p>

    {{-- Invoice Details Card --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 24px;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Property</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">{{ $propertyName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Unit</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $unitName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Amount Due</td>
                        <td style="padding: 8px 0; color: #312e81; font-size: 18px; font-weight: 700; border-top: 1px solid #e5e7eb;">KSh {{ $amount }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Due Date</td>
                        <td style="padding: 8px 0; color: #dc2626; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $dueDate }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Please log in to your Rentify account to view the full invoice details and make your payment.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 16px 0;">
                <a href="{{ url('/tenant/invoices') }}" style="display: inline-block; background-color: #312e81; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600;">
                    View Invoice
                </a>
            </td>
        </tr>
    </table>

    <p style="margin: 16px 0 0 0; color: #9ca3af; font-size: 13px; text-align: center;">
        Please ensure payment is made before the due date to avoid late fees.
    </p>
@endsection
