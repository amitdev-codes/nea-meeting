<!-- resources/views/emails/player-registration-management.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Player Registration - Management Notice</title>
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
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #351bdd 0%, #351bdd 100%);
            padding: 25px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
            background: #ffffff;
            border-radius: 50%;
            padding: 8px;
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
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .slogan {
            color: #dea314;
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }

        .header-subtitle {
            color: #ffffff;
            opacity: 0.9;
            margin-top: 5px;
            font-size: 14px;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .notification-banner {
            background: #e7e7ff;
            color: #351bdd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: center;
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .section-title {
            color: #32408f;
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #351bdd;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 8px;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: 600;
            color: #566a7f;
        }

        .info-value {
            color: #697a8d;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .stat-box {
            background: #ffffff;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: #351bdd;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: #697a8d;
        }

        .action-buttons {
            text-align: center;
            margin: 25px 0;
        }

        .action-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
        }

        .approve-button {
            background-color: #351bdd;
            color: #ffffff;
        }

        .review-button {
            background-color: #f8f9fa;
            color: #566a7f;
            border: 1px solid #d9dee3;
        }

        .status-tag {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff4de;
            color: #ffab00;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .document-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            border: 1px dashed #d9dee3;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #fff;
            background: #dea314;
            font-size: 13px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .content {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                grid-template-columns: 1fr;
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
            <div class="header-subtitle">Player Registration Management Notice</div>
        </div>

        <div class="content">
            <div class="notification-banner">
                New Player Registration Received
            </div>

            <!-- Basic Information Section -->
            <div class="section">
                <h2 class="section-title">Player Information</h2>
                <div class="info-grid">

                    <div class="info-label">Full Name:</div>
                    <div class="info-value">{{ $playerName  ?? ''}}</div>

                    <div class="info-label">Date of Birth:</div>
                    <div class="info-value">{{ $playerDOB ?? ''}}</div>

                    <div class="info-label">Gender:</div>
                    <div class="info-value">{{ $playerGender ?? ''}}</div>

                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $playerAddress ?? ''}}</div>

                    <div class="info-label">Mobile Number:</div>
                    <div class="info-value">{{ $playerMobile ?? ''}}</div>

                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $playerEmail ?? ''}}</div>

                    {{-- <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-tag status-pending">Pending Review</span>
                    </div> --}}
                </div>
            </div>

            <!-- Cricket Information Section -->
            <div class="section">
                <h2 class="section-title">Playstyle Information</h2>
                <div class="info-grid">
                    <div class="info-label">Speciality:</div>
                    <div class="info-value">{{ $playerSpeciality ?? ''}}</div>

                    <div class="info-label">Batting Style:</div>
                    <div class="info-value">{{ $playerBattingStyle ?? ''}}</div>

                    <div class="info-label">Bowling Style:</div>
                    <div class="info-value">{{ $playerBowlingStyle ?? ''}}</div>
                </div>

                {{-- <div class="stats-container">
                    <div class="stat-box">
                        <div class="stat-value">{{ $matchesPlayed }}</div>
                        <div class="stat-label">Matches Played</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $battingAverage }}</div>
                        <div class="stat-label">Batting Average</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $bowlingAverage }}</div>
                        <div class="stat-label">Bowling Average</div>
                    </div>
                </div> --}}
            </div>


            <!-- Previous Teams Section -->

                <div class="section">
                    <h2 class="section-title">Playing History</h2>
                    @if(isset($playerTeam))
                    <div class="info-grid">
                        <div class="info-label">Previous Team:</div>
                        <div class="info-value">{{ $playerTeam ?? ''}}</div>

                        <div class="info-label">Position:</div>
                        <div class="info-value">{{ $playerPosition ?? ''}}</div>

                        <div class="info-label">Province:</div>
                        <div class="info-value">{{ $playerProvience ?? ''}}</div>

                        <div class="info-label">District:</div>
                        <div class="info-value">{{ $playerDistrict ?? ''}}</div>
                    </div>
                    @else
                        <div class="info-value">No previous playing history provided.</div>
                    @endif
                </div>

            {{-- <!-- Medical & Fitness Section -->
            <div class="section">
                <h2 class="section-title">Medical & Fitness Information</h2>
                <div class="info-grid">
                    <div class="info-label">Height:</div>
                    <div class="info-value">{{ $height }} cm</div>

                    <div class="info-label">Weight:</div>
                    <div class="info-value">{{ $weight }} kg</div>

                    <div class="info-label">Blood Group:</div>
                    <div class="info-value">{{ $bloodGroup }}</div>

                    <div class="info-label">Medical History:</div>
                    <div class="info-value">{{ $medicalHistory }}</div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="section">
                <h2 class="section-title">Uploaded Documents</h2>
                <div class="documents-grid">
                    @if ($idProof)
                        <div class="document-box">
                            ID Proof
                            <br>
                            <small>(Click to view)</small>
                        </div>
                    @endif
                    @if ($medicalCertificate)
                        <div class="document-box">
                            Medical Certificate
                            <br>
                            <small>(Click to view)</small>
                        </div>
                    @endif
                    @if ($previousTeamCertificates)
                        <div class="document-box">
                            Previous Team Certificates
                            <br>
                            <small>(Click to view)</small>
                        </div>
                    @endif
                </div>
            </div> --}}

            <!-- Action Buttons -->
            {{-- <div class="action-buttons">
                <a href="{{ $approveUrl }}" class="action-button approve-button">Approve Registration</a>
                <a href="{{ $reviewUrl }}" class="action-button review-button">Review Details</a>
            </div>

            <p><strong>Note:</strong> Please review all information and documents carefully before approving the
                registration. For any queries or concerns, please contact the technical team.</p> --}}
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Sudurpaschim Royals. All rights reserved.</p>
            <small>This is an automated message. Please do not reply to this email.</small>
        </div>
    </div>
</body>

</html>
