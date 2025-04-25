<!-- resources/views/emails/contact-email.blade.php -->
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <div class="header">
            <h1>Sudurpaschim Royals</h1>
            <div class="slogan">Unite. Strike. Conquer.</div>
        </div>

        <div class="content">
            <div class="contact-message">
                Dear Sir/Mam,
            </div>


            <p>{{ $contact->message ?? ''}}</p>

            <p>Best regards,<br>
                Empire IT Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Sudurpaschim Royals. All rights reserved.</p>
            <small>This email was sent to you from Sudurpaschim Royals website's contact form.</small>
        </div>
    </div>
</body>

</html>
