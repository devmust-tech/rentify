@extends('emails.layout')

@section('title', 'Maintenance Request Update - Rentify')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #111827; font-size: 22px; font-weight: 600;">
        Maintenance Request Update
    </h2>

    <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Dear {{ $tenantName }},
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Your maintenance request has been updated. Here are the current details:
    </p>

    {{-- Status Banner --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
        <tr>
            <td style="background-color: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 16px 20px; text-align: center;">
                <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Current Status
                </p>
                <p style="margin: 0; color: #312e81; font-size: 18px; font-weight: 700;">
                    {{ $status }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Request Details Card --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 24px;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Request Title</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">{{ $title }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Property</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $propertyName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">Unit</td>
                        <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600; border-top: 1px solid #e5e7eb;">{{ $unitName }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if ($resolutionNotes)
        {{-- Resolution Notes --}}
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
            <tr>
                <td style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 16px 20px;">
                    <p style="margin: 0 0 8px 0; color: #92400e; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Resolution Notes
                    </p>
                    <p style="margin: 0; color: #78350f; font-size: 14px; line-height: 1.6;">
                        {{ $resolutionNotes }}
                    </p>
                </td>
            </tr>
        </table>
    @endif

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        You can log in to your Rentify account to view the full details of your maintenance request.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 16px 0;">
                <a href="{{ url('/tenant/maintenance') }}" style="display: inline-block; background-color: #312e81; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600;">
                    View Request
                </a>
            </td>
        </tr>
    </table>

    <p style="margin: 16px 0 0 0; color: #9ca3af; font-size: 13px; text-align: center;">
        If you have any questions, please contact your property agent.
    </p>
@endsection
