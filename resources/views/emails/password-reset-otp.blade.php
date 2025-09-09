<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP - Syndeo Wireless</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .logo-symbol {
            color: #fbbf24;
            font-size: 32px;
            margin-right: 10px;
        }
        .content {
            padding: 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            padding: 25px;
            margin: 25px 0;
            border-radius: 12px;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            color: #92400e;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .warning strong {
            color: #b45309;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 10px 0;
        }
        .security-note {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <span class="logo-symbol">⚡</span>SYNDEO WIRELESS
            </div>
            <h2 style="margin: 0; font-weight: 300;">Password Reset OTP</h2>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>You have requested to reset your password for your Syndeo Wireless NOC Provisioning account. Please use the following One-Time Password (OTP) to verify your identity:</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <div class="warning">
                <strong>⚠️ Important Security Information:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>This OTP is valid for <strong>2 minutes only</strong></li>
                    <li>Do not share this OTP with anyone</li>
                    <li>If you didn't request this password reset, please ignore this email</li>
                    <li>Our team will never ask for your OTP via phone or email</li>
                </ul>
            </div>
            
            <div class="security-note">
                <strong>🔒 Security Note:</strong> If you didn't request this password reset, please contact our NOC support team immediately at <strong>noc@syndeowireless.com</strong>
            </div>
            
            <p>Best regards,<br>
            <strong>Syndeo Wireless NOC Team</strong><br>
            <em>NOC Provisioning System</em></p>
        </div>
        
        <div class="footer">
            <p><strong>Syndeo Wireless</strong> - NOC Provisioning System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Syndeo Wireless. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
