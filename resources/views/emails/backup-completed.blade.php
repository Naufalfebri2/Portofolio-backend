<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, sans-serif; background: #f4f4f5; padding: 24px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; padding: 24px;">
        <h2 style="color: #6366f1;">Portfolio Backup Completed</h2>

        <p>Your daily portfolio backup ran successfully.</p>

        <p><strong>Date:</strong> {{ now()->format('d F Y, H:i') }} WIB</p>
        <p><strong>Archive size:</strong> {{ $sizeInMb }} MB</p>
        <p><strong>Contents:</strong> Database dump (database.sql) and all uploaded files (profile photo, project
            thumbnails/gallery, resume PDF).</p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <p style="font-size: 12px; color: #9ca3af;">
            The backup archive is attached to this email. A copy is also kept on the server at
            <code>storage/app/backups/</code> (the 7 most recent backups are retained; older ones are pruned
            automatically).
        </p>
    </div>
</body>

</html>
