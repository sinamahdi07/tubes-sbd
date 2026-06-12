@extends('admin.layouts.app')

@section('title', 'Manajemen Payment')

@push('styles')
<style>
    .data-row { transition: background 0.2s ease; }
    .data-row:hover { background: rgba(17, 141, 255, 0.04); }
    .data-row td { border-bottom: 1px solid rgba(255,255,255,0.04); }
</style>
@endpush

@section('content')

    {{-- ===== STAT MINI CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="premium-card p-5">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Total Payment</p>
            <p class="text-3xl font-black text-white">{{ number_format($stats['total_payments']) }}</p>
        </div>
        <div class="premium-card p-5" style="border-color:rgba(34,197,94,0.25);background:linear-gradient(145deg,rgba(34,197,94,0.08),rgba(34,197,94,0.02))">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-2">Paid</p>
            <p class="text-3xl font-black text-emerald-300">{{ number_format($stats['paid_payments']) }}</p>
        </div>
        <div class="premium-card p-5" style="border-color:rgba(17,141,255,0.25);background:linear-gradient(145deg,rgba(17,141,255,0.08),rgba(17,141,255,0.02))">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-400 mb-2">Revenue</p>
            <p class="text-2xl font-black text-blue-300">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="premium-card p-5" style="border-color:rgba(234,179,8,0.25);background:linear-gradient(145deg,rgba(234,179,8,0.08),rgba(234,179,8,0.02))">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-yellow-400 mb-2">Pending Value</p>
            <p class="text-2xl font-black text-yellow-300">Rp {{ number_format($stats['pending_total'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <form method="GET" action="{{ route('admin.payments.index') }}"
          class="flex items-center gap-3 mb-6 flex-wrap lg:flex-nowrap">

        {{-- Search --}}
        <div class="relative flex-1 min-w-0">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari kode, user, email..."
                   class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm font-medium text-white placeholder-gray-500
                          bg-white/5 border border-white/10 focus:border-[#118dff]/60
                          focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all">
        </div>

        {{-- Status --}}
        <select name="status"
                class="py-2.5 px-3 rounded-xl text-sm font-medium text-white bg-white/5 border border-white/10
                       focus:border-[#118dff]/60 focus:outline-none focus:ring-2 focus:ring-[#118dff]/20
                       flex-shrink-0 w-36 appearance-none cursor-pointer transition-all"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:32px">
            <option value="">Semua Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        {{-- Method --}}
        <select name="method"
                class="py-2.5 px-3 rounded-xl text-sm font-medium text-white bg-white/5 border border-white/10
                       focus:border-[#118dff]/60 focus:outline-none focus:ring-2 focus:ring-[#118dff]/20
                       flex-shrink-0 w-44 appearance-none cursor-pointer transition-all"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:32px">
            <option value="">Semua Metode</option>
            @foreach($methods as $method)
                <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $method)) }}
                </option>
            @endforeach
        </select>

        {{-- Filter --}}
        <button type="submit"
                class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                       bg-white/5 border border-white/10 text-gray-300 hover:bg-[#118dff]/15 hover:border-[#118dff]/40
                       hover:text-white transition-all whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/>
            </svg>
            Filter
        </button>

        @if(request()->hasAny(['search', 'status', 'method']))
            <a href="{{ route('admin.payments.index') }}"
               class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                      text-gray-500 hover:text-red-400 border border-transparent hover:border-red-500/30 transition-all whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Reset
            </a>
        @endif
    </form>

    {{-- ===== TABLE ===== --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5 bg-white/3">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Kode Payment</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">User</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center">Item</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Metode</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-right">Total</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="data-row">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="text-sm font-black text-[#118dff] hover:text-white transition-colors tracking-tight">
                                    {{ $payment->payment_code }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-white">{{ $payment->user->name ?? 'User dihapus' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $payment->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black text-gray-300">{{ $payment->items_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-black uppercase tracking-widest text-gray-400">
                                    {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-400">
                                    {{ $payment->paid_at?->format('d M Y H:i') ?? $payment->created_at->format('d M Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black text-emerald-400">
                                    Rp {{ number_format($payment->display_total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                          bg-[#118dff]/10 text-[#118dff] border border-[#118dff]/20 hover:bg-[#118dff]/20 transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m0-6h2a2 2 0 012 2v2a2 2 0 01-2 2h-2m0-6v6"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">Belum ada payment yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/5 bg-white/2 flex items-center justify-between gap-4">
            <p class="text-xs font-bold text-gray-500">
                Total: <span class="text-gray-300">{{ $payments->total() }}</span> payment
            </p>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
