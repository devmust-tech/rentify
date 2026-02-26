@extends('emails.layout')

@section('title', $subject)

@section('content')
    <h2 style="margin: 0 0 16px 0; color: #111827; font-size: 22px; font-weight: 700; line-height: 1.3;">
        {{ $subject }}
    </h2>

    <div style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.7;">
        {!! nl2br(e($messageBody)) !!}
    </div>

    @if($actionUrl && $actionText)
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin: 24px 0;">
            <tr>
                <td align="center">
                    <a href="{{ $actionUrl }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; font-size: 15px; font-weight: 600; text-decoration: none; padding: 12px 32px; border-radius: 8px; letter-spacing: 0.025em;">
                        {{ $actionText }}
                    </a>
                </td>
            </tr>
        </table>
    @endif

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">

    <p style="margin: 0; color: #9ca3af; font-size: 13px; line-height: 1.5;">
        If you have any questions, please contact your property manager or log in to your Rentify dashboard.
    </p>
@endsection
