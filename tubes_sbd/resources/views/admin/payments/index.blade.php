@extends('admin.layouts.app')

@section('title', 'Manajemen Payment')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="steam-card rounded-lg p-6">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Payment</p>
            <p class="text-3xl font-bold text-white">{{ $stats['total_payments'] }}</p>
        </div>
        <div class="steam-card rounded-lg p-6 border-l-4 border-l-green-500">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Paid</p>
            <p class="text-3xl font-bold text-white">{{ $stats['paid_payments'] }}</p>
        </div>
        <div class="steam-card rounded-lg p-6">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Revenue</p>
            <p class="text-3xl font-bold text-green-400">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="steam-card rounded-lg p-6">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Pending Value</p>
            <p class="text-3xl font-bold text-yellow-400">Rp {{ number_format($stats['pending_total'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid md:grid-cols-4 gap-3 flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode, user, email..."
                class="p-2 steam-input rounded"
            >

            <select name="status" class="p-2 steam-input rounded">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <select name="method" class="p-2 steam-input rounded">
                <option value="">Semua Metode</option>
                @foreach($methods as $method)
                    <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $method)) }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition text-sm">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'method']))
                    <a href="{{ route('admin.payments.index') }}" class="px-3 py-2 text-gray-400 hover:text-white text-sm self-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="steam-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left steam-table">
                <thead>
                    <tr>
                        <th class="p-4">Kode Payment</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Item</th>
                        <th class="p-4">Metode</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td class="p-4">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="font-semibold text-[#66c0f4] hover:text-white">
                                    {{ $payment->payment_code }}
                                </a>
                            </td>
                            <td class="p-4">
                                <div>
                                    <p class="text-white font-medium">{{ $payment->user->name ?? 'User dihapus' }}</p>
                                    <p class="text-gray-500 text-xs">{{ $payment->user->email ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="p-4 text-gray-300">{{ $payment->items_count }}</td>
                            <td class="p-4 text-gray-300">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs rounded {{ $payment->status === 'paid' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-400 text-sm">
                                {{ $payment->paid_at?->format('d M Y H:i') ?? $payment->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="p-4 text-right text-green-400 font-semibold">
                                Rp {{ number_format($payment->display_total, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="px-3 py-1 text-xs bg-[#1b2838] hover:bg-[#2a475e] text-[#66c0f4] border border-[#2a475e] rounded transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m0-6h2a2 2 0 012 2v2a2 2 0 01-2 2h-2m0-6v6"></path>
                                </svg>
                                <p>Belum ada payment yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#2a475e] bg-[#1b2838] flex items-center justify-between">
            <p class="text-sm text-gray-400">Total: {{ $payments->total() }} payment</p>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
