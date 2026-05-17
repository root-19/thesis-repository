<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thesis Atlas - Email Verification</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background-color: #FFFCF2; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(37,36,34,0.06); }
        .otp-box { background: #FFFCF2; border: 2px dashed #CCC5B9; border-radius: 12px; padding: 30px; text-align: center; }
        .otp-code { font-size: 48px; font-weight: 800; letter-spacing: 12px; color: #EB5E28; }
        .footer { font-size: 12px; color: #CCC5B9; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>
        <div class="footer">
            This code expires in 10 minutes.
        </div>
    </div>
</body>
</html>
