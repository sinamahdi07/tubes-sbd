<x-guest-layout>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background: transparent !important;
            color: #f8fafc;
        }

        /* Latar belakang gambar yang di-blur */
        body::before {
            content: "";
            position: fixed;
            inset: -15px;
            pointer-events: none;
            background: url('/images/auth_bg.jpg') center/cover no-repeat;
            filter: blur(6px);
            z-index: -2;
        }

        /* Lapisan gradasi gelap di atas gambar blur agar teks tetap kontras dan terbaca */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(2, 6, 13, .82) 0%, rgba(2, 6, 13, .48) 48%, rgba(2, 6, 13, .78) 100%),
                linear-gradient(135deg, rgba(2, 6, 13, .78), rgba(8, 17, 30, .68));
            z-index: -1;
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
            background: transparent !important;
            border: none !important;
            backdrop-filter: none !important;
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
            width: min(100%, 480px);
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
            font-size: clamp(38px, 8vw, 54px);
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

        .auth-status,
        .auth-alert {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        .auth-status {
            border: 1px solid rgba(52, 211, 153, .34);
            background: rgba(6, 78, 59, .28);
            color: #bbf7d0;
        }

        .auth-alert {
            border: 1px solid rgba(248, 113, 113, .38);
            background: rgba(127, 29, 29, .30);
            color: #fecaca;
        }

        .auth-status-icon,
        .auth-alert-icon {
            display: grid;
            flex: 0 0 auto;
            width: 28px;
            height: 28px;
            place-items: center;
            border-radius: 999px;
            font-weight: 900;
        }

        .auth-status-icon {
            background: rgba(52, 211, 153, .18);
            color: #86efac;
        }

        .auth-alert-icon {
            background: rgba(248, 113, 113, .18);
            color: #fca5a5;
        }

        .auth-form {
            display: grid;
            gap: 18px;
        }

        .auth-field label {
            display: block;
            margin-bottom: 8px;
            color: #66c0f4;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .auth-input {
            display: block;
            width: 100%;
            min-height: 56px;
            border: 1px solid rgba(42, 71, 94, .92);
            border-radius: 12px;
            background: rgba(3, 9, 18, .95);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            outline: none;
            padding: 0 16px;
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .auth-input::placeholder {
            color: #64748b;
        }

        .auth-input:focus {
            border-color: #2fb7ff;
            background: rgba(6, 17, 31, .98);
            box-shadow: 0 0 0 3px rgba(47, 183, 255, .16);
        }

        .auth-input.is-invalid {
            border-color: #f87171;
            background: rgba(69, 10, 10, .34);
        }

        .field-error {
            display: block;
            margin-top: 8px;
            color: #fca5a5;
            font-size: 13px;
            line-height: 1.4;
        }

        .auth-submit,
        .auth-link-button {
            display: inline-flex;
            min-height: 56px;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: .08em;
            text-decoration: none;
            text-transform: uppercase;
            transition: transform .2s ease, filter .2s ease, box-shadow .2s ease, border-color .2s ease, color .2s ease;
        }

        .auth-submit {
            width: 100%;
            border: 0;
            background: linear-gradient(135deg, #06bfff, #2d73ff);
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(17, 141, 255, .28);
        }

        .auth-submit:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 20px 42px rgba(17, 141, 255, .36);
        }

        .auth-link-button {
            width: 100%;
            border: 1px solid rgba(102, 192, 244, .32);
            background: rgba(15, 25, 35, .84);
            color: #66c0f4;
        }

        .auth-link-button:hover {
            border-color: rgba(102, 192, 244, .72);
            color: #ffffff;
        }

        @media (max-width: 640px) {
            .auth-logo {
                width: 68px;
                height: 56px;
            }

            .auth-title {
                font-size: 40px;
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
                <h1 class="auth-title">Reset<span>Password</span></h1>
                <p class="auth-subtitle">Masukkan email akun kamu, lalu cek inbox untuk link reset.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="auth-status" role="status">
                <span class="auth-status-icon">✓</span>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="auth-alert" role="alert">
                <span class="auth-alert-icon">!</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                    class="auth-input @error('email') is-invalid @enderror"
                >
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="auth-submit">Kirim Link Reset</button>
            <a href="{{ route('login') }}" class="auth-link-button">Kembali Login</a>
        </form>
    </section>
</x-guest-layout>
