@extends('layouts.store')

@section('title', 'About - PlayMart')

@push('styles')
    <style>
        .page-panel {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.9), rgba(7, 17, 29, 0.92)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.12), rgba(45, 115, 255, 0.05));
            border: 1px solid rgba(42, 71, 94, 0.9);
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush

@section('content')
    <main class="mx-auto w-full max-w-[1700px] px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="relative min-h-[360px] overflow-hidden rounded-lg border border-[#2a475e]/90 bg-[#07111d] md:min-h-[460px]"
            style="background-image: linear-gradient(90deg, rgba(5,10,18,.96) 0%, rgba(5,10,18,.78) 42%, rgba(5,10,18,.18) 100%), url('https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1800&auto=format&fit=crop'); background-size: cover; background-position: center;"
        >
            <div class="flex min-h-[360px] max-w-3xl flex-col justify-center px-6 py-12 md:min-h-[460px] md:px-12">
                <p class="text-sm font-black uppercase tracking-[0.24em] text-[#66c0f4]">About PlayMart</p>
                <h1 class="mt-4 text-4xl font-black leading-tight text-white md:text-6xl">PlayMart adalah website toko game digital.</h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-gray-300 md:text-lg">
                    Website ini dibuat untuk tugas kuliah sebagai simulasi toko game online, mulai dari pencarian game, cart, checkout, sampai review setelah pembelian.
                </p>
            </div>
        </section>

        <section class="mt-8 grid gap-4 md:grid-cols-3">
            <div class="page-panel rounded-lg p-6">
                <p class="text-3xl font-black text-white">Store</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">User bisa melihat daftar game, diskon, category, genre, dan informasi detail sebelum membeli.</p>
            </div>
            <div class="page-panel rounded-lg p-6">
                <p class="text-3xl font-black text-white">Checkout</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Game yang dipilih masuk ke cart, lalu user bisa memilih item mana saja yang ingin dibeli.</p>
            </div>
            <div class="page-panel rounded-lg p-6">
                <p class="text-3xl font-black text-white">Admin</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-400">Admin dapat mengelola game, category, review, user, payment, developer, publisher, dan platform.</p>
            </div>
        </section>

        <section class="mt-10 grid gap-6 lg:grid-cols-[1fr_1fr]">
            <div class="page-panel rounded-lg p-6 md:p-8">
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Tentang Website</p>
                <h2 class="mt-3 text-3xl font-black text-white">Apa itu PlayMart</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-400">
                    PlayMart adalah website marketplace game digital yang meniru alur pembelian game seperti platform distribusi game modern. Di website ini, pengunjung dapat melihat game yang tersedia, mencari game berdasarkan nama, memilih genre atau category, melihat harga serta diskon, lalu membuka halaman detail game.
                </p>
                <p class="mt-4 text-sm leading-relaxed text-gray-400">
                    Setelah login, user dapat menambahkan game ke cart, melakukan checkout, melihat riwayat payment, dan memberi review berupa recommended atau not recommended untuk game yang sudah dibeli.
                </p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-5">
                        <h3 class="font-black text-white">Katalog Game</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-400">Browse game, filter genre dan category, lihat diskon, lalu masuk ke detail game.</p>
                    </div>
                    <div class="rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-5">
                        <h3 class="font-black text-white">Transaksi</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-400">User memilih game di cart, memilih item yang ingin dibeli, lalu payment tercatat.</p>
                    </div>
                    <div class="rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-5">
                        <h3 class="font-black text-white">Review Pembeli</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-400">Review hanya bisa dibuat oleh user yang sudah membeli game tersebut.</p>
                    </div>
                    <div class="rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-5">
                        <h3 class="font-black text-white">Admin Panel</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-400">Admin bisa mengelola master data dan melihat aktivitas store.</p>
                    </div>
                </div>
            </div>

            <div class="page-panel rounded-lg p-6 md:p-8">
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Tujuan Website</p>
                <h2 class="mt-3 text-3xl font-black text-white">Dibuat untuk tugas kuliah</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-400">
                    PlayMart dibuat sebagai proyek tugas kuliah untuk menerapkan alur website toko game digital. Di dalamnya ada fitur user dan admin yang saling terhubung dengan data game, category, cart, payment, dan review.
                </p>
                <div class="mt-6 space-y-4">
                    <div class="flex gap-4 rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#118dff] text-sm font-black text-white">1</span>
                        <p class="text-sm leading-relaxed text-gray-300">User dapat mencari game dan melihat informasi seperti harga, diskon, genre, category, dan detail game.</p>
                    </div>
                    <div class="flex gap-4 rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#118dff] text-sm font-black text-white">2</span>
                        <p class="text-sm leading-relaxed text-gray-300">Cart menyimpan game pilihan user sebelum diproses ke checkout.</p>
                    </div>
                    <div class="flex gap-4 rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#118dff] text-sm font-black text-white">3</span>
                        <p class="text-sm leading-relaxed text-gray-300">Payment menyimpan data transaksi, sedangkan payment item menyimpan game yang dibeli.</p>
                    </div>
                    <div class="flex gap-4 rounded-lg border border-[#2a475e] bg-[#0f1923]/82 p-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#118dff] text-sm font-black text-white">4</span>
                        <p class="text-sm leading-relaxed text-gray-300">Admin panel digunakan untuk mengelola data utama agar isi website tetap teratur.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
