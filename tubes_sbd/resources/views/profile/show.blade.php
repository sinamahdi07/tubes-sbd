@extends('layouts.store')

@section('title', 'Profile Dashboard - PlayMart')

@push('styles')
    <style>
        .profile-container-main {
            background: radial-gradient(circle at top right, rgba(17, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(45, 115, 255, 0.05), transparent 40%);
        }
        .profile-card-premium {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2.5rem;
        }
        .stat-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .stat-card:hover {
            transform: translateY(-8px);
            border-color: rgba(102, 192, 244, 0.3);
            background: rgba(102, 192, 244, 0.05);
        }
        .avatar-glow {
            box-shadow: 0 0 30px rgba(17, 141, 255, 0.3);
        }
        .payment-row {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 1.25rem;
            transition: all 0.2s ease;
        }
        .payment-row:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.01);
        }
    </style>
@endpush

@section('content')
    <div class="profile-container-main min-h-screen py-10 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="profile-card-premium p-8 lg:p-12 mb-10 overflow-hidden relative">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 h-64 w-64 rounded-full bg-[#118dff] opacity-5 blur-[100px]"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="flex flex-col sm:flex-row items-center gap-8">
                        <div class="relative">
                            <div class="avatar-glow h-32 w-32 rounded-[2.5rem] bg-gradient-to-tr from-[#118dff] to-[#66c0f4] flex items-center justify-center text-5xl font-black text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl border-4 border-[#07111d] bg-success flex items-center justify-center text-white shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full bg-[#66c0f4]/10 text-[#66c0f4] text-[10px] font-black uppercase tracking-widest border border-[#66c0f4]/20">Gamer Profile</span>
                                @if(auth()->user()->is_admin)
                                    <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest border border-red-500/20">Administrator</span>
                                @endif
                            </div>
                            <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-white mb-2">{{ $user->name }}</h1>
                            <p class="text-gray-400 font-medium flex items-center justify-center sm:justify-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('profile.edit') }}" class="group relative inline-flex items-center justify-center gap-3 overflow-hidden rounded-2xl bg-white px-8 py-4 text-base font-black text-black transition-all hover:scale-105 active:scale-95 shadow-xl">
                            <span>Edit Account</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-red-500/10 text-red-500 font-black border border-red-500/20 hover:bg-red-500 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12">
                    <a href="{{ route('profile.games') }}" class="stat-card p-6 flex items-center gap-6 group">
                        <div class="h-16 w-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-4xl font-black text-white tracking-tight">{{ $purchasedGamesCount }}</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Games Owned</p>
                        </div>
                    </a>

                    <a href="{{ route('friends.index') }}" class="stat-card p-6 flex items-center gap-6 group">
                        <div class="h-16 w-16 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-4xl font-black text-white tracking-tight">{{ $friendCount }}</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Global Friends</p>
                        </div>
                    </a>

                    <a href="{{ route('payments.history') }}" class="stat-card p-6 flex items-center gap-6 group">
                        <div class="h-16 w-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-4xl font-black text-white tracking-tight">{{ $paidPayments->count() }}</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Transactions</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity / Payments -->
            <div class="profile-card-premium p-8 lg:p-12">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-white">Recent Transactions</h2>
                        <p class="text-gray-400 mt-1 font-medium">Melacak riwayat pembelian game terbaru Anda.</p>
                    </div>
                    <a href="{{ route('payments.history') }}" class="inline-flex items-center gap-2 text-sm font-black text-[#66c0f4] hover:text-white transition-colors">
                        View All History
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if($latestPayments->count() > 0)
                    <div class="space-y-4">
                        @foreach($latestPayments as $payment)
                            <a href="{{ route('payments.show', $payment) }}" class="payment-row block p-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                    <div class="flex items-center gap-5">
                                        <div class="h-14 w-14 rounded-2xl bg-white/5 flex items-center justify-center text-[#66c0f4]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-white tracking-tight mb-1">{{ $payment->payment_code }}</h3>
                                            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">
                                                {{ $payment->items->sum('quantity') }} items &bull; {{ $payment->paid_at?->format('D, d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-8 justify-between md:justify-end">
                                        <div class="text-right">
                                            <p class="text-2xl font-black text-[#66c0f4]">Rp {{ number_format($payment->display_total, 0, ',', '.') }}</p>
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-500/10 px-2 py-0.5 rounded-lg">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Success
                                            </span>
                                        </div>
                                        <div class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-white/20 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-black/20 border border-dashed border-white/10 rounded-[2rem] p-16 text-center">
                        <div class="h-20 w-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 text-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white tracking-tighter">No transactions yet</h3>
                        <p class="text-gray-400 mt-2 mb-8 max-w-xs mx-auto">Mulai belanja game favoritmu dan riwayat transaksi akan muncul di sini.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-[#118dff] text-white font-black shadow-xl shadow-blue-500/20 hover:scale-105 transition-all">
                            Browse Store
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
