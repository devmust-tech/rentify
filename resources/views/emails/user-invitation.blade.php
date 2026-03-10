@extends('emails.layout')

@section('title', 'You\'ve been invited to Rentify')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #111827; font-size: 22px; font-weight: 600;">
        You've been invited
    </h2>

    <p style="margin: 0 0 16px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        Dear {{ $userName }},
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 15px; line-height: 1.6;">
        <strong>{{ $orgName }}</strong> has added you as a {{ $role }} on Rentify. Click the button below to set up your password and access your {{ $role }} portal.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 24px 0;">
                <a href="{{ $inviteUrl }}" style="display: inline-block; background-color: #312e81; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600;">
                    Set Up My Account
                </a>
            </td>
        </tr>
    </table>

    {{-- Expiry notice --}}
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffbeb; border-radius: 8px; border: 1px solid #fde68a; margin-bottom: 24px;">
        <tr>
            <td style="padding: 14px 18px;">
                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                    This invitation link expires in <strong>72 hours</strong>. If you did not expect this email, you can safely ignore it.
                </p>
            </td>
        </tr>
    </table>

    <p style="margin: 0; color: #9ca3af; font-size: 13px; text-align: center;">
        If the button doesn't work, copy and paste this link into your browser:<br>
        <span style="color: #6b7280; word-break: break-all;">{{ $inviteUrl }}</span>
    </p>
@endsection
