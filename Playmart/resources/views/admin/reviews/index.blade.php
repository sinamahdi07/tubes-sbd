@extends('admin.layouts.app')

@section('title', 'Manajemen Review')

@push('styles')
<style>
    .data-row { transition: background 0.2s ease; }
    .data-row:hover { background: rgba(17, 141, 255, 0.04); }
    .data-row td { border-bottom: 1px solid rgba(255,255,255,0.04); }
</style>
@endpush

@section('content')
    @php use Illuminate\Support\Str; @endphp

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="premium-card p-5">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Total Review</p>
            <p class="text-3xl font-black text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="premium-card p-5" style="border-color:rgba(34,197,94,0.25);background:linear-gradient(145deg,rgba(34,197,94,0.08),rgba(34,197,94,0.02))">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-2">👍 Like</p>
            <p class="text-3xl font-black text-emerald-300">{{ number_format($stats['likes']) }}</p>
        </div>
        <div class="premium-card p-5" style="border-color:rgba(239,68,68,0.25);background:linear-gradient(145deg,rgba(239,68,68,0.08),rgba(239,68,68,0.02))">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-red-400 mb-2">👎 Dislike</p>
            <p class="text-3xl font-black text-red-300">{{ number_format($stats['dislikes']) }}</p>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <form method="GET" action="{{ route('admin.reviews.index') }}"
          class="flex items-center gap-3 mb-6 flex-wrap lg:flex-nowrap">

        {{-- Search --}}
        <div class="relative flex-1 min-w-0">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari game, user, atau isi review..."
                   class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm font-medium text-white placeholder-gray-500
                          bg-white/5 border border-white/10 focus:border-[#118dff]/60
                          focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all">
        </div>

        {{-- Sentiment --}}
        <select name="sentiment"
                class="py-2.5 px-3 rounded-xl text-sm font-medium text-white bg-white/5 border border-white/10
                       focus:border-[#118dff]/60 focus:outline-none focus:ring-2 focus:ring-[#118dff]/20
                       flex-shrink-0 w-40 appearance-none cursor-pointer transition-all"
                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:32px">
            <option value="">Semua Review</option>
            <option value="like"    @selected($sentiment === 'like')>👍 Like</option>
            <option value="dislike" @selected($sentiment === 'dislike')>👎 Dislike</option>
        </select>

        <button type="submit"
                class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                       bg-white/5 border border-white/10 text-gray-300 hover:bg-[#118dff]/15 hover:border-[#118dff]/40
                       hover:text-white transition-all whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/>
            </svg>
            Filter
        </button>

        @if(request()->hasAny(['search', 'sentiment']))
            <a href="{{ route('admin.reviews.index') }}"
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
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Game</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">User</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Review</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center w-24">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 w-32">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="data-row">
                            {{-- Game --}}
                            <td class="px-6 py-4">
                                <a href="{{ url('/game/' . $review->game_id) }}" target="_blank"
                                   class="flex items-center gap-3 hover:text-[#118dff] transition-colors group">
                                    @if($review->game?->thumbnail_url)
                                        <img src="{{ $review->game->thumbnail_url }}" alt="{{ $review->game->title }}"
                                             class="h-9 w-14 rounded-lg object-cover flex-shrink-0 group-hover:ring-2 ring-[#118dff]/40 transition-all">
                                    @else
                                        <span class="h-9 w-14 rounded-lg bg-white/5 flex-shrink-0"></span>
                                    @endif
                                    <span class="text-sm font-bold text-white group-hover:text-[#118dff] transition-colors">
                                        {{ $review->game?->title ?? 'Game dihapus' }}
                                    </span>
                                </a>
                            </td>

                            {{-- User --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-white">{{ $review->user?->name ?? 'User dihapus' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $review->user?->email ?? '-' }}</p>
                            </td>

                            {{-- Review Body --}}
                            <td class="px-6 py-4 max-w-sm">
                                <p class="text-sm text-gray-300 leading-relaxed line-clamp-2">
                                    {{ Str::limit($review->body, 130) }}
                                </p>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($review->is_recommended)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        👍 Like
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-red-500/10 text-red-400 border border-red-500/20">
                                        👎 Dislike
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-400">
                                    {{ $review->created_at?->format('d M Y') ?? '-' }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline"
                                      onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin menghapus review ini?', 'danger', 'Hapus Review');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                   bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">Belum ada review yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/5 bg-white/2 flex items-center justify-between gap-4">
            <p class="text-xs font-bold text-gray-500">
                Total: <span class="text-gray-300">{{ $reviews->total() }}</span> review
            </p>
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
