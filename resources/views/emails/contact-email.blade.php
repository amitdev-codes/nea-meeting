<!-- resources/views/emails/contact-email.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Sudurpaschim Royals</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f9;
            color: #566a7f;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #351bdd 0%, #351bdd 100%);
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .logo-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 15px;
            background: #ffffff;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            color: #dea314;
            margin: 15px 0 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .slogan {
            color: #dea314;
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }

        .content {
            background: #eef;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .contact-message {
            font-size: 18px;
            margin-bottom: 25px;
            color: #32408f;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #fff;
            font-size: 14px;
            background: #dea314;
            /* border-top: 1px solid #e9ecef; */
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .content {
                padding: 20px;
            }

            .logo-container {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ $message->embed(public_path('assets/img/sudurpaschim-logo.png')) }}"
                    alt="Sudurpaschim Royals Logo" class="logo">
            </div>
            <h1>Sudurpaschim Royals</h1>
            <div class="slogan">Unite. Strike. Conquer.</div>
        </div>

        <div class="content">
            <div class="contact-message">
                Dear Sir/Mam,
            </div>

            <p>You have received a new contact message from {{ $contact->name ?? ''  }}:</p>

            <p><strong>Email:</strong> {{ $contact->email ?? '' }}</p>
            <p><strong>Subject:</strong> {{ $contact->subject ?? '' }}</p>
            <p><strong>Message:</strong></p>
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
