@extends('emails.layout')

@section('title', 'Payment Confirmed - Rentify')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #111827; font-size: 22px; font-weight: 600;">
        Payment Confirmed
    </h2>

    <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Dear {{ $tenantName }},
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        We have received your payment. Thank you for your prompt settlement!
    </p>

    {{-- Success Banner --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
        <tr>
            <td style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 16px 20px; text-align: center;">
                <p style="margin: 0; color: #065f46; font-size: 14px; font-weight: 600;">
                    Payment Successfully Processed
                </p>
            </td>
        </tr>
    </table>

    {{-- Payment Details Card --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 24px;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Amount Paid</td>
                        <td style="padding: 8px 0; color: #065f46; font-size: 18px; font-weight: 700;">KSh {{ $amount }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Payment Method</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $method }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Reference</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $reference }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Property</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $propertyName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Unit</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $unitName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Date</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $paidAt }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        You can view your payment history and download receipts by logging in to your Rentify account.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 16px 0;">
                <a href="{{ url('/tenant/payments') }}" style="display: inline-block; background-color: #312e81; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600;">
                    View Payment History
                </a>
            </td>
        </tr>
    </table>

    <p style="margin: 16px 0 0 0; color: #9ca3af; font-size: 13px; text-align: center;">
        Keep this email as your payment confirmation record.
    </p>
@endsection
