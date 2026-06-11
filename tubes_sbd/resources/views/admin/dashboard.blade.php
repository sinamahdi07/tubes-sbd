@extends('admin.layouts.app')

@section('title', 'Admin Hub Dashboard')

@push('styles')
    <style>
        .stat-icon-bg {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
        }
        .data-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        .data-row:hover {
            background: rgba(17, 141, 255, 0.05);
        }
        .revenue-glow {
            text-shadow: 0 0 20px rgba(52, 211, 153, 0.3);
        }
    </style>
@endpush

@section('content')
    <!-- Statistics Overdrive -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-10">
        @php
            $statCards = [
                ['label' => 'Games', 'value' => $stats['total_games'], 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                ['label' => 'Users', 'value' => $stats['total_users'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Devs', 'value' => $stats['total_developers'], 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                ['label' => 'Pubs', 'value' => $stats['total_publishers'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['label' => 'Genres', 'value' => $stats['total_genres'], 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['label' => 'Cats', 'value' => $stats['total_categories'], 'icon' => 'M4 7h16M4 12h16M4 17h10'],
                ['label' => 'Reviews', 'value' => $stats['total_reviews'], 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z'],
                ['label' => 'Pay', 'value' => $stats['total_payments'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        @foreach($statCards as $card)
            <div class="premium-card p-4 flex flex-col items-center text-center group">
                <div class="h-10 w-10 rounded-xl stat-icon-bg flex items-center justify-center text-gray-500 mb-3 group-hover:text-[#118dff] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $card['icon'] }}"></path></svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $card['label'] }}</span>
                <span class="text-xl font-black text-white tracking-tighter">{{ number_format($card['value']) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Revenue Spotlight -->
    <div class="premium-card p-8 mb-10 overflow-hidden relative group">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 h-40 w-40 rounded-full bg-emerald-500 opacity-5 blur-3xl transition-opacity group-hover:opacity-10"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="h-16 w-16 rounded-3xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shadow-lg shadow-emerald-500/5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Total Revenue Paid</span>
                    <p class="text-4xl lg:text-5xl font-black text-emerald-400 mt-1 tracking-tighter revenue-glow">
                        <span class="text-2xl text-emerald-600 mr-1">Rp</span>{{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-black font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105 active:scale-95">
                Manage All Payments
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
        <!-- Recent Games Panel -->
        <div class="premium-card overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-2 w-2 rounded-full bg-[#118dff] animate-pulse"></div>
                    <h2 class="text-lg font-black tracking-tighter text-white uppercase">Recent Entries</h2>
                </div>
                <a href="{{ route('admin.games.index') }}" class="text-[10px] font-black text-[#118dff] hover:text-white uppercase tracking-widest transition-colors">Full Catalogue</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-600 bg-black/20">
                            <th class="px-8 py-4">Title & Details</th>
                            <th class="px-8 py-4">Price</th>
                            <th class="px-8 py-4">Release</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium">
                        @forelse($recentGames as $game)
                            <tr class="data-row group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-8 rounded-lg overflow-hidden bg-white/5 shadow-lg">
                                            @if($game->thumbnail_url)
                                                <img src="{{ $game->thumbnail_url }}" alt="" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-700">NA</div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-white font-black truncate leading-none mb-1">{{ $game->title }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $game->publisher->name ?? 'Indie' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-emerald-400 font-black">Rp{{ number_format($game->price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-gray-500 font-bold">
                                    {{ $game->release_date ? $game->release_date->format('M d, Y') : 'TBA' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-8 py-12 text-center text-gray-600 italic">No games deployed yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Intel Panel -->
        <div class="premium-card overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-2 w-2 rounded-full bg-purple-500 animate-pulse"></div>
                    <h2 class="text-lg font-black tracking-tighter text-white uppercase">User Intel</h2>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-black text-purple-400 hover:text-white uppercase tracking-widest transition-colors">Registry</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-600 bg-black/20">
                            <th class="px-8 py-4">Account</th>
                            <th class="px-8 py-4">Auth Level</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium">
                        @forelse($recentUsers as $user)
                            <tr class="data-row">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-gray-800 to-gray-700 flex items-center justify-center text-white font-black shadow-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-white font-black truncate leading-none mb-1">{{ $user->name }}</p>
                                            <p class="text-[10px] text-gray-500 font-bold">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-widest border border-red-500/20 shadow-lg shadow-red-500/5">
                                            <span class="h-1 w-1 rounded-full bg-red-500"></span>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/5 text-gray-400 text-[9px] font-black uppercase tracking-widest border border-white/5">
                                            <span class="h-1 w-1 rounded-full bg-gray-600"></span>
                                            User
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-8 py-12 text-center text-gray-600 italic">Registry is empty.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Global Transaction Log -->
        <div class="premium-card overflow-hidden xl:col-span-2">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <h2 class="text-lg font-black tracking-tighter text-white uppercase">Transaction Log</h2>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-[10px] font-black text-emerald-400 hover:text-white uppercase tracking-widest transition-colors">Ledger Audit</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-600 bg-black/20">
                            <th class="px-8 py-4">Control Code</th>
                            <th class="px-8 py-4">Account Holder</th>
                            <th class="px-8 py-4">Payload</th>
                            <th class="px-8 py-4">Protocol</th>
                            <th class="px-8 py-4">State</th>
                            <th class="px-8 py-4 text-right">Value</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium">
                        @forelse($recentPayments as $payment)
                            <tr class="data-row group">
                                <td class="px-8 py-5">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="font-black text-[#118dff] hover:underline decoration-2 underline-offset-4">{{ $payment->payment_code }}</a>
                                </td>
                                <td class="px-8 py-5 text-white font-bold">{{ $payment->user->name ?? 'TERMINATED' }}</td>
                                <td class="px-8 py-5 text-gray-500 font-bold uppercase tracking-tighter">{{ $payment->items_count }} UNIT(S)</td>
                                <td class="px-8 py-5 text-[10px] font-black uppercase text-gray-500 tracking-widest">{{ str_replace('_', ' ', $payment->method) }}</td>
                                <td class="px-8 py-5">
                                    @if($payment->status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">
                                            PAID
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">
                                            PENDING
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right font-black text-white">
                                    Rp{{ number_format($payment->display_total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-8 py-12 text-center text-gray-600 italic">No activity detected.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
