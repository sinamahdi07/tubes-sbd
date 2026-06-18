<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email PlayMart</title>
</head>
<body style="margin: 0; padding: 0; background: #07111d; color: #e5edf7; font-family: Arial, Helvetica, sans-serif;">
    <span style="display: none; max-height: 0; overflow: hidden; opacity: 0;">
        Verifikasi email kamu untuk mulai menggunakan akun PlayMart.
    </span>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: #07111d; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 36px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 640px; border-collapse: collapse;">
                    <tr>
                        <td align="center" style="padding: 0 0 22px;">
                            <div style="display: inline-block; width: 54px; height: 54px; border-radius: 16px; background: #0f2638; border: 1px solid #2fb7ff; color: #66c0f4; font-size: 28px; font-weight: 900; line-height: 54px; text-align: center;">
                                P
                            </div>
                            <div style="margin-top: 14px; font-size: 28px; font-weight: 900; letter-spacing: 0; color: #ffffff;">
                                Play<span style="color: #66c0f4;">Mart</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="overflow: hidden; border: 1px solid #2a475e; border-radius: 14px; background: #0b1624; box-shadow: 0 18px 42px rgba(0, 0, 0, 0.28);">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 34px 34px 26px;">
                                        <p style="margin: 0 0 10px; color: #66c0f4; font-size: 13px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase;">
                                            Verifikasi Akun
                                        </p>

                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; line-height: 1.25; font-weight: 900;">
                                            Halo, {{ $user->name }}.
                                        </h1>

                                        <p style="margin: 18px 0 0; color: #c9d6e6; font-size: 16px; line-height: 1.7;">
                                            Tinggal satu langkah lagi. Klik tombol di bawah ini untuk mengaktifkan akun PlayMart kamu dan mulai menjelajahi katalog game.
                                        </p>

                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 28px 0 26px; border-collapse: collapse;">
                                            <tr>
                                                <td style="border-radius: 10px; background: #118dff;">
                                                    <a href="{{ $url }}" style="display: inline-block; padding: 15px 26px; color: #ffffff; font-size: 15px; font-weight: 900; text-decoration: none;">
                                                        Verifikasi Email
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin: 0; color: #8ea4bd; font-size: 14px; line-height: 1.7;">
                                            Link ini berlaku selama {{ $expireMinutes }} menit. Kalau kamu tidak membuat akun PlayMart, abaikan email ini.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 34px 30px; border-top: 1px solid #22384e; background: #08111d;">
                                        <p style="margin: 0 0 10px; color: #c9d6e6; font-size: 14px; line-height: 1.6;">
                                            Tombol tidak bisa diklik? Salin link ini ke browser:
                                        </p>
                                        <a href="{{ $url }}" style="color: #66c0f4; font-size: 13px; line-height: 1.6; word-break: break-all;">
                                            {{ $url }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 22px 8px 0; color: #7b8da3; font-size: 12px; line-height: 1.6;">
                            &copy; {{ now()->year }} {{ $appName }}. Semua hak dilindungi.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
