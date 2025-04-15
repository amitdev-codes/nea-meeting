<!-- resources/views/emails/player-registration.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sudurpaschim Royals</title>
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

        .slogan {
            color: #dea314;
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
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

        .header-subtitle {
            color: #ffffff;
            opacity: 0.9;
            margin-top: 5px;
            font-size: 16px;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .welcome-message {
            font-size: 18px;
            margin-bottom: 25px;
            color: #32408f;
        }

        .registration-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }

        .registration-id {
            background: #e7e7ff;
            color: #696cff;
            padding: 8px 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 16px;
            display: inline-block;
            margin-top: 5px;
        }

        .verify-button {
            display: inline-block;
            background-color: #351bdd;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(105, 108, 255, 0.3);
        }

        .verify-button:hover {
            background-color: #dea314;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #fff;
            background: #dea314;
            font-size: 14px;
        }

        .important-note {
            border-left: 4px solid #351bdd;
            padding-left: 15px;
            margin: 20px 0;
            color: #566a7f;
            background: #fafbff;
            padding: 15px;
            border-radius: 0 8px 8px 0;
        }

        .contact-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px 20px;
            margin-top: 20px;
        }

        .contact-details h3 {
            color: #32408f;
            margin-top: 0;
        }

        .social-links {
            margin: 20px 0;
            padding: 15px 0;
            border-top: 1px solid #e9ecef;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #351bdd;
            text-decoration: none;
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
            <div class="welcome-message">
                Dear {{ $playerName }},
            </div>

            {{-- <p>Congratulations and welcome to the Sudurpaschim Royals family! 🎉 We are thrilled to confirm your
                registration as a {{ $playerSpeciality }} in our esteemed cricket team.</p> --}}
            <p>
                Thank you for your interest in joining the Sudurpaschim Royals! 🎉 We are thrilled to confirm your
                registration in our esteemed cricket team. Stay tuned for updates as we work to build our team.
            </p>

            <div class="registration-details">
                <p><strong>Registration Details:</strong></p>
                <p>Player Name: {{ $playerName }}</p>
                <p>Speciality: {{ $playerSpeciality }}</p>
                <p>Contact Number: {{ $playerMobile }}</p>
                <p>Email: {{ $playerEmail }}</p>
                {{-- <p>Registration ID: <span class="registration-id">{{ $registrationId }}</span></p> --}}
                {{-- <p><small>Please save this ID for future reference</small></p> --}}
            </div>

            {{-- <div class="important-note">
                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>Please verify your email address by clicking the button below</li>
                    <li>Complete your player profile with additional information</li>
                    <li>Review the team schedule and training timings</li>
                    <li>Download our team app to stay updated</li>
                </ul>
            </div> --}}

            {{-- <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    Verify Email Address
                </a>
            </div> --}}

            <p>Your journey with Sudurpaschim Royals begins now! We value your commitment and look forward to seeing you
                showcase your skills on the field. Our coaching staff will be in touch with you shortly regarding
                training schedules and team orientations.</p>

            <div class="contact-details">
                <h3>Team Management Contact Information</h3>
                <p><strong>Email:</strong> info@sudurpaschimroyals.com</p>
                <p><strong>Phone:</strong> +977-01-5922607</p>
                <p><strong>Office Hours:</strong> Sunday to Friday, 9:00 AM - 5:00 PM</p>
            </div>

            <div class="social-links">
                <p><strong>Follow us on social media:</strong></p>
                <a href="https://www.facebook.com/suparoyals">Facebook</a>
                <a href="https://x.com/suparoyals">Twitter</a>
                <a href="https://www.instagram.com/suparoyals">Instagram</a>
                <a href="https://www.youtube.com/suparoyals">Youtube</a>
                <a href="https://www.tiktok.com/suparoyals">TikTok</a>
            </div>

            <p>Best regards,<br>
                Team Management<br>
                Sudurpaschim Royals</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Sudurpaschim Royals. All rights reserved.</p>
            <small>This email was sent to you because you submitted your registration to Sudurpaschim Royals.</small>
        </div>
    </div>
</body>

</html>
