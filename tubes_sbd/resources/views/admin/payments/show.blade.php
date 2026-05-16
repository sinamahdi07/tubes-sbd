@extends('admin.layouts.app')

@section('title', 'Detail Payment: ' . $payment->payment_code)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke daftar payment
        </a>

        <a href="{{ route('payments.show', $payment) }}" class="px-4 py-2 text-sm font-semibold rounded steam-btn-primary" target="_blank">
            Lihat Receipt User
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-5 border-b border-[#2a475e] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Kode Payment</p>
                        <h1 class="text-3xl font-bold text-white">{{ $payment->payment_code }}</h1>
                    </div>
                    <span class="px-3 py-2 text-sm rounded font-bold uppercase {{ $payment->status === 'paid' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                        {{ $payment->status }}
                    </span>
                </div>

                <div class="p-6 grid md:grid-cols-3 gap-5">
                    <div class="bg-[#1b2838] border border-[#2a475e] rounded p-4">
                        <p class="text-gray-400 text-sm mb-1">Subtotal</p>
                        <p class="text-xl font-bold text-white">Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-[#1b2838] border border-[#2a475e] rounded p-4">
                        <p class="text-gray-400 text-sm mb-1">Diskon</p>
                        <p class="text-xl font-bold text-yellow-400">Rp {{ number_format($payment->discount_total, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-[#1b2838] border border-[#2a475e] rounded p-4">
                        <p class="text-gray-400 text-sm mb-1">Total</p>
                        <p class="text-xl font-bold text-green-400">Rp {{ number_format($payment->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                    <h2 class="font-bold text-[#66c0f4]">Item Game Dibeli</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left steam-table">
                        <thead>
                            <tr>
                                <th class="p-4">Game</th>
                                <th class="p-4">Harga</th>
                                <th class="p-4">Diskon</th>
                                <th class="p-4">Qty</th>
                                <th class="p-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->items as $item)
                                <tr>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->game?->thumbnail_url)
                                                <img src="{{ $item->game->thumbnail_url }}" alt="{{ $item->title }}" class="w-16 h-9 object-cover rounded border border-gray-700">
                                            @else
                                                <div class="w-16 h-9 bg-gray-800 rounded border border-gray-700 flex items-center justify-center text-xs text-gray-500">N/A</div>
                                            @endif
                                            <div>
                                                <p class="text-white font-semibold">{{ $item->title }}</p>
                                                <p class="text-xs text-gray-500">Game ID: {{ $item->game_id ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-gray-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="p-4 text-yellow-400">{{ $item->discount_percent }}%</td>
                                    <td class="p-4 text-gray-300">{{ $item->quantity }}</td>
                                    <td class="p-4 text-right text-green-400 font-semibold">
                                        Rp {{ number_format($item->line_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                    <h2 class="font-bold text-[#66c0f4]">Informasi User</h2>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Nama</span>
                        <span class="text-white text-right font-medium">{{ $payment->user->name ?? 'User dihapus' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Email</span>
                        <span class="text-white text-right font-medium">{{ $payment->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">User ID</span>
                        <span class="text-white text-right font-medium">{{ $payment->user_id }}</span>
                    </div>
                </div>
            </div>

            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                    <h2 class="font-bold text-[#66c0f4]">Informasi Payment</h2>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Metode</span>
                        <span class="text-white text-right font-medium">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Dibayar</span>
                        <span class="text-white text-right font-medium">{{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Dibuat</span>
                        <span class="text-white text-right font-medium">{{ $payment->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400">Diperbarui</span>
                        <span class="text-white text-right font-medium">{{ $payment->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
