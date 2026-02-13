<!DOCTYPE html>
<html>
<body style="font-family: 'Plus Jakarta Sans', sans-serif; color: #0F172A; line-height: 1.5;">
    <div style="max-width: 600px; margin: 0 auto; padding: 40px; border: 1px solid #F1F5F9; border-radius: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: -0.025em;">PAYGRID</h2>
        <p style="margin-top: 24px;">Hello,</p>
        <p>You have been invited to join <strong>{{ $invitation->organization->name }}</strong> as a <strong>{{ ucfirst($invitation->role) }}</strong>.</p>

        <div style="margin: 32px 0;">
            <a href="{{ $url }}" style="background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 13px; display: inline-block; text-transform: uppercase;">Accept Invitation</a>
        </div>

        <p style="font-size: 11px; color: #64748B; font-style: italic;">
            This invitation is valid for <strong>72 hours</strong>. After that, the link will expire for security reasons.
        </p>
    </div>
</body>
</html>
