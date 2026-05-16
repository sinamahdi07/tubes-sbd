@php
use Illuminate\Contracts\Auth\MustVerifyEmail;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - PlayMart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #1b2838;
            color: white;
            font-family: Arial, Helvetica, sans-serif;
        }

        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
        }

        .profile-input {
            background: #0f1923;
            border: 1px solid #316282;
            color: #fff;
        }

        .profile-input:focus {
            border-color: #66c0f4;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 192, 244, 0.16);
        }
    </style>
</head>
<body class="min-h-screen">
    <nav class="bg-[#171a21] border-b border-[#2a475e] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full steam-blue flex items-center justify-center font-bold text-xl">
                    G
                </div>
                <h1 class="text-2xl font-bold tracking-wide text-[#66c0f4]">
                    PlayMart
                </h1>
            </a>

            <div class="hidden md:flex gap-8 text-sm uppercase tracking-wider font-semibold text-gray-300">
                <a href="{{ route('home') }}" class="hover:text-white">Store</a>
                <a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a>
            </div>

            <x-store-user-menu />
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-5">
            <div>
                <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-3">
                    Account
                </p>
                <h1 class="text-4xl font-bold mb-2">
                    Edit Profile
                </h1>
                <p class="text-gray-400">
                    Atur nama, email, password, dan keamanan akun PlayMart kamu.
                </p>
            </div>

            <a href="{{ route('profile.show') }}" class="bg-[#2a475e] hover:bg-[#35617d] px-6 py-3 rounded-xl font-bold transition text-center">
                Detail Profile
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <section class="lg:col-span-2 space-y-8">
                <article class="glass border border-[#2a475e] rounded-3xl p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">
                            Informasi Profile
                        </h2>
                        <p class="text-gray-400 mt-1">
                            Ubah nama tampilan dan email akun.
                        </p>
                    </div>

                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">
                                Nama
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                                autocomplete="name"
                                class="profile-input w-full rounded-xl px-4 py-3"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">
                                Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="username"
                                class="profile-input w-full rounded-xl px-4 py-3"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror

                            @if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-4 rounded-2xl border border-yellow-500/40 bg-yellow-500/10 p-4 text-sm text-yellow-100">
                                    Email kamu belum terverifikasi.
                                    <button form="send-verification" class="font-bold underline hover:text-white">
                                        Kirim ulang link verifikasi.
                                    </button>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-green-200">
                                            Link verifikasi baru sudah dikirim ke email kamu.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <button type="submit" class="steam-blue px-6 py-3 rounded-xl font-bold hover:opacity-90 transition">
                                Simpan Profile
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-300">
                                    Profile berhasil disimpan.
                                </p>
                            @endif
                        </div>
                    </form>
                </article>

                <article class="glass border border-[#2a475e] rounded-3xl p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">
                            Update Password
                        </h2>
                        <p class="text-gray-400 mt-1">
                            Gunakan password yang kuat supaya akun tetap aman.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-300 mb-2">
                                Password Saat Ini
                            </label>
                            <input
                                id="update_password_current_password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                                class="profile-input w-full rounded-xl px-4 py-3"
                            >
                            @if($errors->updatePassword->has('current_password'))
                                <p class="mt-2 text-sm text-red-300">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password" class="block text-sm font-semibold text-gray-300 mb-2">
                                Password Baru
                            </label>
                            <input
                                id="update_password_password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                class="profile-input w-full rounded-xl px-4 py-3"
                            >
                            @if($errors->updatePassword->has('password'))
                                <p class="mt-2 text-sm text-red-300">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">
                                Konfirmasi Password
                            </label>
                            <input
                                id="update_password_password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                class="profile-input w-full rounded-xl px-4 py-3"
                            >
                            @if($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-2 text-sm text-red-300">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <button type="submit" class="steam-blue px-6 py-3 rounded-xl font-bold hover:opacity-90 transition">
                                Simpan Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-green-300">
                                    Password berhasil diubah.
                                </p>
                            @endif
                        </div>
                    </form>
                </article>
            </section>

            <aside class="space-y-8">
                <article class="glass border border-[#2a475e] rounded-3xl p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full steam-blue flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-2xl font-bold truncate">
                                {{ $user->name }}
                            </h2>
                            <p class="text-gray-400 truncate">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm font-semibold">
                        <a href="{{ route('profile.show') }}" class="block rounded-xl bg-[#0f1923] border border-[#2a475e] px-4 py-3 hover:border-[#66c0f4] transition">
                            Detail Profile
                        </a>
                        <a href="{{ route('profile.games') }}" class="block rounded-xl bg-[#0f1923] border border-[#2a475e] px-4 py-3 hover:border-[#66c0f4] transition">
                            Game Dibeli
                        </a>
                        <a href="{{ route('friends.index') }}" class="block rounded-xl bg-[#0f1923] border border-[#2a475e] px-4 py-3 hover:border-[#66c0f4] transition">
                            Teman
                        </a>
                    </div>
                </article>

                <article class="border border-red-500/40 rounded-3xl p-8 bg-red-950/20">
                    <h2 class="text-2xl font-bold text-red-100">
                        Hapus Akun
                    </h2>
                    <p class="text-red-100/80 mt-2 mb-5">
                        Akun dan semua data terkait akan dihapus permanen.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4" onsubmit="return confirm('Yakin hapus akun ini?')">
                        @csrf
                        @method('delete')

                        <div>
                            <label for="delete_password" class="block text-sm font-semibold text-red-100 mb-2">
                                Password
                            </label>
                            <input
                                id="delete_password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password"
                                class="w-full rounded-xl border border-red-400/50 bg-[#0f1923] px-4 py-3 text-white outline-none focus:border-red-300"
                            >
                            @if($errors->userDeletion->has('password'))
                                <p class="mt-2 text-sm text-red-200">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-red-600 px-5 py-3 font-bold text-white transition hover:bg-red-700">
                            Hapus Akun
                        </button>
                    </form>
                </article>
            </aside>
        </div>
    </main>
</body>
</html>
