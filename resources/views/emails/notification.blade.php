<!DOCTYPE html>
<html>
<head>
    <title> Notifikasi Claim</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f6f8;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e1e8ed;
        }
        .email-header {
            background-color: #34495e;
            padding: 35px 40px;
            text-align: left;
            border-bottom: 3px solid #3498db;
        }
        .email-content {
            padding: 40px;
        }
        h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 500;
        }
        .content-text {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 25px;
            line-height: 1.7;
        }
        .action-button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff !important; /* Penting untuk kompatibilitas */
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            font-size: 15px;
        }
        .footer-text {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #ecf0f1;
        }
        .button-container {
            margin: 30px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $greeting }}</h1>
        </div>
        
        <div class="email-content">
            {{-- Loop untuk menampilkan semua baris pesan --}}
            @foreach ($lines as $line)
                <p class="content-text">{!! $line !!}</p>
            @endforeach
            
            @if ($actionText && $actionUrl && ! $actionText2)
            <div class="button-container">
                <a href="{{ $actionUrl }}" class="action-button" target="_blank">
                    {{ $actionText }}
                </a>
            </div>
            @elseif($actionText2 && $actionUrl2)
            <div class="button-container">
                <a href="{{ $actionUrl2 }}" class="action-button" target="_blank" style="background:#10b981; margin-right:8px;">{{ $actionText2 }}</a>
                <a href="{{ $actionUrl }}" class="action-button" target="_blank" style="background:#ef4444;">{{ $actionText }}</a>
            </div>
            @endif
            
            <p class="footer-text">
                Jika Anda merasa tidak melakukan tindakan ini, Anda bisa mengabaikan email ini. <br>
                Hormat kami, Tim Indosmart.
            </p>
        </div>
    </div>
</body>
</html>