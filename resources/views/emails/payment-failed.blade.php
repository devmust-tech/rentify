<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Payment Failed</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 24px; color: #111827; }
        .card { background: #fff; border-radius: 12px; max-width: 560px; margin: 0 auto; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .icon { width: 48px; height: 48px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
        h2 { font-size: 20px; font-weight: 700; margin: 0 0 8px; }
        p { font-size: 15px; color: #4b5563; line-height: 1.6; margin: 0 0 16px; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; margin: 8px 0 24px; }
        .footer { font-size: 13px; color: #9ca3af; margin-top: 32px; border-top: 1px solid #f3f4f6; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Subscription Payment Failed</h2>
        <p>Hi {{ $ownerName }},</p>
        <p>
            We were unable to process the subscription payment for <strong>{{ $orgName }}</strong>.
            Your account is currently in a grace period — please update your payment method as soon as
            possible to avoid service interruption.
        </p>
        <a href="{{ $billingUrl }}" class="btn">Update Payment Method</a>
        <p>
            If you have any questions, reply to this email and our support team will help.
        </p>
        <div class="footer">
            You're receiving this because you are the account owner for {{ $orgName }} on Rentify.
        </div>
    </div>
</body>
</html>
