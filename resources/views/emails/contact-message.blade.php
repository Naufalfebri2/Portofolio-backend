<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, sans-serif; background: #f4f4f5; padding: 24px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; padding: 24px;">
        <h2 style="color: #10b981;">New Message from Portfolio</h2>

        <p><strong>Name:</strong> {{ $contactMessage->name }}</p>
        <p><strong>Email:</strong> {{ $contactMessage->email }}</p>

        @if ($contactMessage->company)
            <p><strong>Company:</strong> {{ $contactMessage->company }}</p>
        @endif

        @if ($contactMessage->subject)
            <p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
        @endif

        @if ($contactMessage->event_date)
            <p><strong>Interview/Meeting Date:</strong> {{ $contactMessage->event_date->format('d F Y') }}</p>
        @endif

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <p style="white-space: pre-line;">{{ $contactMessage->message }}</p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <p style="font-size: 12px; color: #9ca3af;">This message was sent automatically from the portfolio contact form.
        </p>
    </div>
</body>

</html>
