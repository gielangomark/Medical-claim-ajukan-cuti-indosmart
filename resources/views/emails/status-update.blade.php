<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $subject }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            font-family: 'Inter', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #f4f7f6;
            color: #3d4852;
        }
        table {
            border-collapse: collapse;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s ease;
        }
        .action-button:hover {
            transform: translateY(-1px);
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f4f7f6;">
        <tr>
            <td align="center">
                <table class="email-container" width="600" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    
                    <tr>
                        <td style="padding: 32px 40px; border-bottom: 1px solid #e2e8f0;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <img src="{{ asset('indosmart_update.png') }}" alt="Logo Indosmart" style="height: 32px;" onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
                                    </td>
                                    <td style="text-align: right; font-size: 14px; color: #64748b;">
                                        Sistem Notifikasi
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px; color: #3d4852; font-size: 16px; line-height: 1.6;">
                            <h1 style="color: #1e293b; font-size: 24px; font-weight: 700; margin-top: 0; margin-bottom: 24px;">
                                {{ $greeting }}
                            </h1>
                            
                            {{-- Loop untuk menampilkan semua baris pesan --}}
                            @foreach ($lines as $line)
                                <p style="margin-top: 0; margin-bottom: 20px;">
                                    {!! $line !!}
                                </p>
                            @endforeach

                            {{-- Tombol Aksi (Call to Action) --}}
                            @if ($actionText && $actionUrl)
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}" class="action-button" target="_blank">
                                            {{ $actionText }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <p style="margin-top: 32px; margin-bottom: 0;">
                                Hormat kami,<br>
                                Tim Indosmart
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px 40px; border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="text-align: center; font-size: 12px; color: #94a3b8;">
                                        <p style="margin: 0 0 8px 0;">Ini adalah email yang dibuat secara otomatis. Mohon untuk tidak membalas email ini.</p>
                                        <p style="margin: 0;">&copy; {{ date('Y') }} Indosmart. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>