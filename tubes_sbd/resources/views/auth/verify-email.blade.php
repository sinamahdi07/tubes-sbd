<x-guest-layout>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background:
                linear-gradient(135deg, rgba(2, 6, 13, .78), rgba(8, 17, 30, .68)),
                url('/images/The-Best-Survival-Games-For-Android (1).jpg');
            background-position: center;
            background-size: cover;
            color: #f8fafc;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(90deg, rgba(2, 6, 13, .82) 0%, rgba(2, 6, 13, .48) 48%, rgba(2, 6, 13, .78) 100%);
        }

        body > .min-h-screen {
            position: relative;
            display: grid;
            min-height: 100vh;
            padding: 24px;
            place-items: center;
        }

        body > .min-h-screen > div {
            display: flex;
            width: 100% !important;
            max-width: none !important;
            min-height: calc(100vh - 48px);
            align-items: center;
            justify-content: center;
            margin-top: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            box-shadow: none !important;
        }

        @media (max-height: 760px) {
            body > .min-h-screen {
                place-items: start center;
            }

            body > .min-h-screen > div {
                min-height: 0;
                align-items: flex-start;
            }
        }

        .auth-card {
            position: relative;
            z-index: 1;
            width: min(100%, 500px);
            border: 1px solid rgba(102, 192, 244, .24);
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(15, 25, 35, .96), rgba(4, 9, 17, .96));
            box-shadow: 0 28px 80px rgba(0, 0, 0, .55), inset 0 1px 0 rgba(255, 255, 255, .05);
            padding: 34px;
        }

        .auth-card,
        .auth-card * {
            box-sizing: border-box;
        }

        .auth-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            text-align: center;
        }

        .auth-logo {
            display: grid;
            width: 78px;
            height: 64px;
            place-items: center;
            overflow: hidden;
            border-radius: 16px;
            background: rgba(10, 29, 53, .88);
            padding: 7px;
            box-shadow: 0 0 0 1px rgba(102, 192, 244, .34), 0 14px 34px rgba(47, 183, 255, .16);
        }

        .auth-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-title {
            margin: 0;
            color: #ffffff;
            font-size: clamp(36px, 8vw, 52px);
            font-weight: 900;
            line-height: .95;
            letter-spacing: 0;
        }

        .auth-title span {
            color: #66c0f4;
        }

        .auth-subtitle {
            margin-top: 12px;
            color: #9aa7b5;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.5;
        }

        .verify-panel {
            margin-bottom: 20px;
            border: 1px solid rgba(42, 71, 94, .8);
            border-radius: 16px;
            background: rgba(3, 9, 18, .62);
            padding: 18px;
        }

        .verify-email {
            margin: 0;
            color: #ffffff;
            font-size: 15px;
            font-weight: 900;
            overflow-wrap: anywhere;
        }

        .verify-copy {
            margin: 8px 0 0;
            color: #9aa7b5;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.6;
        }

        .auth-status {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            border: 1px solid rgba(52, 211, 153, .34);
            border-radius: 14px;
            background: rgba(6, 78, 59, .28);
            color: #bbf7d0;
            padding: 14px 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        .auth-status-icon {
            display: grid;
            flex: 0 0 auto;
            width: 28px;
            height: 28px;
            place-items: center;
            border-radius: 999px;
            background: rgba(52, 211, 153, .18);
            color: #86efac;
            font-weight: 900;
        }

        .auth-actions {
            display: grid;
            gap: 12px;
        }

        .auth-submit,
        .auth-secondary {
            display: inline-flex;
            min-height: 56px;
            width: 100%;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: transform .2s ease, filter .2s ease, box-shadow .2s ease, border-color .2s ease, color .2s ease;
        }

        .auth-submit {
            border: 0;
            background: linear-gradient(135deg, #06bfff, #2d73ff);
            color: #ffffff;
            box-shadow: 0 16px 34px rgba(17, 141, 255, .28);
        }

        .auth-submit:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 20px 42px rgba(17, 141, 255, .36);
        }

        .auth-secondary {
            border: 1px solid rgba(102, 192, 244, .32);
            background: rgba(15, 25, 35, .84);
            color: #66c0f4;
        }

        .auth-secondary:hover {
            border-color: rgba(102, 192, 244, .72);
            color: #ffffff;
        }

        @media (max-width: 640px) {
            .auth-logo {
                width: 68px;
                height: 56px;
            }

            .auth-title {
                font-size: 38px;
            }

            .auth-card {
                border-radius: 16px;
                padding: 26px 20px;
            }
        }
    </style>

    <section class="auth-card">
        <div class="auth-brand">
            <a href="{{ route('home') }}" class="auth-logo" aria-label="PlayMart home">
                <img src="{{ asset('GAMESTORE.png') }}" alt="PlayMart">
            </a>
            <div>
                <h1 class="auth-title">Verifikasi<span>Email</span></h1>
                <p class="auth-subtitle">Link verifikasi sudah dikirim ke email akun kamu.</p>
            </div>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-status" role="status">
                <span class="auth-status-icon">✓</span>
                <div>Link verifikasi baru sudah dikirim. Cek inbox, spam, atau promotions.</div>
            </div>
        @endif

        <div class="verify-panel">
            <p class="verify-email">{{ auth()->user()->email }}</p>
            <p class="verify-copy">
                Klik link dari email untuk mengaktifkan akun. Kalau belum masuk, kamu bisa kirim ulang dari tombol di bawah.
            </p>
        </div>

        <div class="auth-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-submit">Kirim Ulang Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-secondary">Logout</button>
            </form>
        </div>
    </section>
</x-guest-layout>
