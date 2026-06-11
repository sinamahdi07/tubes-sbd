<x-guest-layout>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background-attachment: scroll;
            background:
                radial-gradient(circle at 18% 18%, rgba(47, 183, 255, .13), transparent 34%),
                linear-gradient(135deg, rgba(2, 6, 13, .72), rgba(8, 17, 30, .62)),
                url('/images/The-Best-Survival-Games-For-Android (1).jpg');
            background-position: center;
            background-size: cover;
            color: #f8fafc;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(2, 6, 13, .78) 0%, rgba(2, 6, 13, .46) 48%, rgba(2, 6, 13, .74) 100%),
                radial-gradient(circle at 78% 20%, rgba(102, 192, 244, .12), transparent 28%);
        }

        body > .min-h-screen {
            position: relative;
            display: grid;
            min-height: 100vh;
            padding: 24px;
<<<<<<< HEAD

            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
=======
            place-items: center;
>>>>>>> 8de6f1e4e3b970ccba1eda9c099aaa0891cb7b9a
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
            margin-bottom: 32px;
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
            font-size: clamp(42px, 8vw, 58px);
            font-weight: 900;
            line-height: .95;
            letter-spacing: 0;
            color: #ffffff;
        }

        .auth-title span {
            color: #66c0f4;
        }

        .auth-subtitle {
            margin-top: 14px;
            color: #9aa7b5;
            font-size: 17px;
            font-weight: 700;
        }

        .auth-form {
            display: grid;
            gap: 17px;
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

        .auth-input-wrap {
            position: relative;
            width: 100%;
        }

        .auth-input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            min-height: 54px;
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

        .auth-input.has-toggle {
            padding-right: 54px;
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

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border: 1px solid rgba(42, 71, 94, .75);
            border-radius: 10px;
            background: rgba(15, 25, 35, .9);
            color: #9aa7b5;
            cursor: pointer;
            transform: translateY(-50%);
            transition: color .2s ease, border-color .2s ease, background .2s ease;
        }

        .password-toggle:hover,
        .password-toggle.is-visible {
            border-color: rgba(102, 192, 244, .75);
            background: rgba(17, 141, 255, .12);
            color: #66c0f4;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

<<<<<<< HEAD
        .hidden {
            display: none !important;
=======
        .password-toggle [data-eye-closed],
        .password-toggle.is-visible [data-eye-open] {
            display: none;
        }

        .password-toggle.is-visible [data-eye-closed] {
            display: block;
>>>>>>> 8de6f1e4e3b970ccba1eda9c099aaa0891cb7b9a
        }

        .field-error {
            margin-top: 8px;
            color: #fca5a5;
            font-size: 13px;
            line-height: 1.4;
        }

        .field-error li {
            list-style: none;
        }

        .auth-submit {
            min-height: 56px;
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #06bfff, #2d73ff);
            color: #ffffff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
            box-shadow: 0 16px 34px rgba(17, 141, 255, .28);
            transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
        }

        .auth-submit:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 20px 42px rgba(17, 141, 255, .36);
        }

        .auth-switch {
            margin-top: 2px;
            border-top: 1px solid rgba(42, 71, 94, .7);
            padding-top: 18px;
            text-align: center;
            color: #9aa7b5;
            font-size: 15px;
            font-weight: 700;
        }

        .auth-link {
            color: #66c0f4;
            font-weight: 900;
            text-decoration: none;
            transition: color .2s ease;
        }

        .auth-link:hover {
            color: #ffffff;
        }

            @media (max-width: 768px) {

        body > .min-h-screen {
            padding: 16px;
        }

        .auth-card {
            width: 100%;
            max-width: 100%;
            padding: 24px 18px;
            border-radius: 16px;
        }

        .auth-logo {
            width: 60px;
            height: 50px;
        }

        .auth-title {
            font-size: clamp(32px, 10vw, 42px);
        }

        .auth-subtitle {
            font-size: 14px;
        }

        .auth-input {
            min-height: 50px;
            font-size: 14px;
        }

        .auth-submit {
            min-height: 50px;
            font-size: 14px;
        }

        .password-toggle {
            width: 34px;
            height: 34px;
        }
    }

            @media (max-width: 480px) {

        body > .min-h-screen {
            padding: 12px;
        }

        .auth-card {
            padding: 20px 16px;
            border-radius: 14px;
        }

        .auth-brand {
            margin-bottom: 20px;
        }

        .auth-title {
            font-size: 34px;
        }

        .auth-subtitle {
            font-size: 13px;
        }

        .auth-field label {
            font-size: 11px;
        }

        .auth-input {
            min-height: 48px;
            font-size: 14px;
        }

        .auth-submit {
            min-height: 48px;
            font-size: 13px;
            letter-spacing: .05em;
        }

        .auth-switch {
            font-size: 13px;
        }
    }
    </style>

    <section class="auth-card">
        <div class="auth-brand">
            <a href="{{ route('home') }}" class="auth-logo" aria-label="PlayMart home">
                <img src="{{ asset('GAMESTORE.png') }}" alt="PlayMart">
            </a>
            <div>
                <h1 class="auth-title">Play<span>Mart</span></h1>
                <p class="auth-subtitle">Create your account</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="name">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nama kamu"
                    class="auth-input @error('name') is-invalid @enderror"
                >
                <x-input-error :messages="$errors->get('name')" class="field-error" />
            </div>

            <div class="auth-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="nama@email.com"
                    class="auth-input @error('email') is-invalid @enderror"
                >
                <x-input-error :messages="$errors->get('email')" class="field-error" />
            </div>

            <div class="auth-field">
                <label for="password">Password</label>
                <div class="auth-input-wrap">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="auth-input has-toggle @error('password') is-invalid @enderror"
                        data-password-input
                    >
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">
                        <svg data-eye-open xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5Z"/>
                        </svg>
                        <svg data-eye-closed class="hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.6 10.6A3.25 3.25 0 0 0 12 15.25c1.8 0 3.25-1.45 3.25-3.25 0-.5-.11-.96-.31-1.38M7.4 7.67C4.25 9.58 2.25 12 2.25 12S6 18.75 12 18.75c1.72 0 3.19-.38 4.44-.95M12.94 5.3c5.43.6 8.81 6.7 8.81 6.7a19.1 19.1 0 0 1-2.48 3.18"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="field-error" />
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="auth-input-wrap">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="auth-input has-toggle @error('password_confirmation') is-invalid @enderror"
                        data-password-input
                    >
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">
                        <svg data-eye-open xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5Z"/>
                        </svg>
                        <svg data-eye-closed class="hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.6 10.6A3.25 3.25 0 0 0 12 15.25c1.8 0 3.25-1.45 3.25-3.25 0-.5-.11-.96-.31-1.38M7.4 7.67C4.25 9.58 2.25 12 2.25 12S6 18.75 12 18.75c1.72 0 3.19-.38 4.44-.95M12.94 5.3c5.43.6 8.81 6.7 8.81 6.7a19.1 19.1 0 0 1-2.48 3.18"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="field-error" />
            </div>

            <button type="submit" class="auth-submit">Register</button>

            <p class="auth-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="auth-link">Sign In</a>
            </p>
        </form>
    </section>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            const wrap = button.closest('.auth-input-wrap');
            const input = wrap?.querySelector('[data-password-input]');
            const eyeOpen = button.querySelector('[data-eye-open]');
            const eyeClosed = button.querySelector('[data-eye-closed]');

            button.addEventListener('click', () => {
                if (!input) return;

                const visible = input.type === 'text';
                input.type = visible ? 'password' : 'text';
                button.classList.toggle('is-visible', !visible);
                button.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
                button.setAttribute('aria-pressed', String(!visible));
                eyeOpen?.classList.toggle('hidden', !visible);
                eyeClosed?.classList.toggle('hidden', visible);
                input.focus();
            });
        });
    </script>
</x-guest-layout>
