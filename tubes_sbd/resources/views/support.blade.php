@extends('layouts.store')

@section('title', 'Support - PlayMart')

@push('styles')
    <style>
        .page-panel {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.9), rgba(7, 17, 29, 0.92)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.12), rgba(45, 115, 255, 0.05));
            border: 1px solid rgba(42, 71, 94, 0.9);
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.3);
        }
        details summary { list-style: none; }
        details summary::-webkit-details-marker { display: none; }
        details[open] summary {
            color: #66c0f4;
            border-bottom: 1px solid rgba(102, 192, 244, 0.2);
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <main class="mx-auto w-full max-w-[1700px] px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="relative min-h-[260px] overflow-hidden rounded-lg border border-[#2a475e]/90 bg-[#07111d] md:min-h-[430px]"
            style="background-image: linear-gradient(90deg, rgba(5,10,18,.96) 0%, rgba(5,10,18,.78) 45%, rgba(5,10,18,.2) 100%), url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1800&auto=format&fit=crop'); background-size: cover; background-position: center;"
        >
            <div class="flex min-h-[260px] max-w-3xl flex-col justify-center px-6 py-8 md:min-h-[430px] md:px-12">
                <p class="text-sm font-black uppercase tracking-[0.24em] text-[#66c0f4]">Support Center</p>
                <h1 class="mt-3 text-3xl font-black leading-tight text-white md:text-6xl">Bantuan untuk akun, cart, checkout, dan game.</h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-gray-300 md:text-lg border-l-4 border-[#118dff] pl-6">
                    Cek panduan cepat untuk masalah umum di PlayMart, dari game tidak masuk cart sampai status payment.
                </p>
            </div>
        </section>

        <section class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('games.search') }}" class="page-panel block rounded-lg p-6 transition hover:border-[#66c0f4]">
                <span class="flex h-10 w-10 items-center justify-center rounded bg-[#0b2a44] text-[#66c0f4]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                </span>
                <h2 class="mt-4 text-lg font-black text-white">Cari Game</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Temukan game lewat search, genre, dan category.</p>
            </a>

            <a href="{{ route('cart.index') }}" class="page-panel block rounded-lg p-6 transition hover:border-[#66c0f4]">
                <span class="flex h-10 w-10 items-center justify-center rounded bg-[#0b2a44] text-[#66c0f4]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l2.1 11.1a2 2 0 0 0 2 1.65h7.9a2 2 0 0 0 1.96-1.6L20 8H6M9 20.25h.01M17 20.25h.01"/>
                    </svg>
                </span>
                <h2 class="mt-4 text-lg font-black text-white">Cart</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Lihat game yang sudah ditambahkan sebelum checkout.</p>
            </a>

            <a href="{{ route('payments.history') }}" class="page-panel block rounded-lg p-6 transition hover:border-[#66c0f4]">
                <span class="flex h-10 w-10 items-center justify-center rounded bg-[#0b2a44] text-[#66c0f4]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2Z"/>
                    </svg>
                </span>
                <h2 class="mt-4 text-lg font-black text-white">Payment</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Cek riwayat transaksi dan game yang sudah dibayar.</p>
            </a>

            <a href="{{ route('profile.games') }}" class="page-panel block rounded-lg p-6 transition hover:border-[#66c0f4]">
                <span class="flex h-10 w-10 items-center justify-center rounded bg-[#0b2a44] text-[#66c0f4]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11ZM8 8h8M8 12h8M8 16h5"/>
                    </svg>
                </span>
                <h2 class="mt-4 text-lg font-black text-white">Library</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Buka daftar game yang sudah kamu miliki.</p>
            </a>
        </section>

        <section class="mt-10 grid gap-6 lg:grid-cols-[1fr_1fr]">
            <div class="page-panel rounded-lg p-6 md:p-8">
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">FAQ</p>
                <h2 class="mt-3 text-3xl font-black text-white">Pertanyaan umum</h2>

                <div class="mt-8 space-y-4">
                    <details class="group rounded-xl border border-[#2a475e] bg-[#0f1923]/82 p-5 transition-all duration-300 open:bg-[#16202d]" open>
                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-lg font-bold text-white">
                            Game tidak bisa ditambah ke cart
                            <span class="text-[#66c0f4] transition-transform group-open:rotate-45">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-gray-400">Satu user hanya bisa menambahkan satu game yang sama ke cart. Kalau sudah ada, angka cart tetap satu untuk game itu.</p>
                    </details>

                    <details class="group rounded-xl border border-[#2a475e] bg-[#0f1923]/82 p-5 transition-all duration-300 open:bg-[#16202d]">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-lg font-bold text-white">
                            Diskon tidak muncul di checkout
                            <span class="text-[#66c0f4] transition-transform group-open:rotate-45">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-gray-400">Diskon diambil dari detail game. Jika admin mengubah diskon, harga checkout akan memakai nilai diskon terbaru.</p>
                    </details>

                    <details class="group rounded-xl border border-[#2a475e] bg-[#0f1923]/82 p-5 transition-all duration-300 open:bg-[#16202d]">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-lg font-bold text-white">
                            Kenapa tidak bisa review
                            <span class="text-[#66c0f4] transition-transform group-open:rotate-45">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-gray-400">Review hanya bisa dibuat setelah user punya payment berstatus paid untuk game tersebut.</p>
                    </details>

                    <details class="group rounded-xl border border-[#2a475e] bg-[#0f1923]/82 p-5 transition-all duration-300 open:bg-[#16202d]">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-lg font-bold text-white">
                            Popular Right Now dihitung dari apa
                            <span class="text-[#66c0f4] transition-transform group-open:rotate-45">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-gray-400">Rankingnya dihitung dari jumlah item payment pada transaksi yang statusnya paid.</p>
                    </details>
                </div>
            </div>

            <div class="page-panel rounded-lg p-6 md:p-8">
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Kontak</p>
                <h2 class="mt-3 text-3xl font-black text-white">Email Support</h2>
                <div class="mt-6 rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-5">
                    <p class="text-sm leading-relaxed text-gray-400">
                        Kirim pertanyaan atau laporan masalah ke email support PlayMart.
                    </p>
                    <div class="mt-5 grid gap-3 text-sm font-semibold text-gray-300">
                        <div class="flex items-center justify-between gap-4 rounded border border-[#2a475e] px-4 py-3">
                            <span>Email support</span>
                            <a href="mailto:support@playmart.test" class="text-[#66c0f4] transition hover:text-white">support@playmart.test</a>
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-lg border border-[#2a475e] bg-[#07111d]/80 p-5">
                    <h3 class="font-black text-white">Checklist saat demo</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-400">
                        <p>Pastikan user login sebelum tambah cart.</p>
                        <p>Pastikan cart berisi item sebelum checkout.</p>
                        <p>Pastikan payment status paid untuk membuka review dan library.</p>
                        <p>Pastikan category sudah dipilih di admin agar tampil di store.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
