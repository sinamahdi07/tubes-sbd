@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-6 mb-8">
        <div class="steam-card rounded-lg p-6 flex flex-col justify-center items-center">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Games</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_games'] }}</span>
        </div>
        <div class="steam-card rounded-lg p-6 flex flex-col justify-center items-center border-l-4 border-l-[#66c0f4]">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Users</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</span>
        </div>
        <div class="steam-card rounded-lg p-6 flex flex-col justify-center items-center">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Developers</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_developers'] }}</span>
        </div>
        <div class="steam-card rounded-lg p-6 flex flex-col justify-center items-center">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Publishers</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_publishers'] }}</span>
        </div>
        <div class="steam-card rounded-lg p-6 flex flex-col justify-center items-center">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Genres</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_genres'] }}</span>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="steam-card rounded-lg p-6 flex flex-col justify-center items-center hover:border-[#66c0f4] transition">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Categories</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_categories'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="steam-card rounded-lg p-6 flex flex-col justify-center items-center hover:border-[#66c0f4] transition">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Reviews</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_reviews'] }}</span>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="steam-card rounded-lg p-6 flex flex-col justify-center items-center hover:border-[#66c0f4] transition">
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Payments</span>
            <span class="text-3xl font-bold text-white">{{ $stats['total_payments'] }}</span>
        </a>
    </div>

    <div class="steam-card rounded-lg p-6 mb-8 border-l-4 border-l-green-500">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Revenue Paid</span>
                <p class="text-4xl font-bold text-green-400 mt-2">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </p>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 steam-btn-primary rounded font-semibold text-sm text-center">
                Lihat Payment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Games -->
        <div class="steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex justify-between items-center">
                <h2 class="text-lg font-bold text-[#66c0f4]">Game Terbaru</h2>
                <a href="{{ route('admin.games.index') }}" class="text-xs text-gray-400 hover:text-white">Lihat Semua</a>
            </div>
            <div class="p-0">
                <table class="w-full text-left steam-table">
                    <thead>
                        <tr>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Rilis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGames as $game)
                            <tr>
                                <td class="p-3">
                                    <div class="flex items-center gap-3">
                                        @if($game->thumbnail_url)
                                            <img src="{{ $game->thumbnail_url }}" alt="{{ $game->title }}" class="w-12 h-6 object-cover rounded">
                                        @else
                                            <div class="w-12 h-6 bg-gray-700 rounded flex items-center justify-center text-xs">No Img</div>
                                        @endif
                                        <span class="font-medium text-white">{{ $game->title }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-green-400">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-gray-400 text-sm">{{ $game->release_date ? $game->release_date->format('d M Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">Belum ada game.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex justify-between items-center">
                <h2 class="text-lg font-bold text-[#66c0f4]">User Terbaru</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-400 hover:text-white">Lihat Semua</a>
            </div>
            <div class="p-0">
                <table class="w-full text-left steam-table">
                    <thead>
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td class="p-3 text-white">{{ $user->name }}</td>
                                <td class="p-3 text-gray-400">{{ $user->email }}</td>
                                <td class="p-3">
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-200">Admin</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">User</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">Belum ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="steam-card rounded-lg overflow-hidden lg:col-span-2">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex justify-between items-center">
                <h2 class="text-lg font-bold text-[#66c0f4]">Payment Terbaru</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs text-gray-400 hover:text-white">Lihat Semua</a>
            </div>
            <div class="p-0">
                <table class="w-full text-left steam-table">
                    <thead>
                        <tr>
                            <th class="p-3">Kode</th>
                            <th class="p-3">User</th>
                            <th class="p-3">Item</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td class="p-3">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="font-semibold text-[#66c0f4] hover:text-white">
                                        {{ $payment->payment_code }}
                                    </a>
                                </td>
                                <td class="p-3 text-gray-300">{{ $payment->user->name ?? 'User dihapus' }}</td>
                                <td class="p-3 text-gray-400">{{ $payment->items_count }}</td>
                                <td class="p-3 text-gray-400">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded {{ $payment->status === 'paid' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-right text-green-400 font-semibold">
                                    Rp {{ number_format($payment->display_total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">Belum ada payment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
