@extends('layouts.store')

@section('title', 'Chat dengan ' . $friend->name . ' - PlayMart')

@push('styles')
    <style>
        .chat-thread-surface {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.9), rgba(7, 17, 29, 0.94)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.1), rgba(45, 115, 255, 0.04));
        }

        .chat-bubble {
            position: relative;
            max-width: min(76%, 620px);
            border-radius: 10px;
            padding: 7px 11px 7px 12px;
            font-size: 15px;
            line-height: 1.35;
            border: 1px solid rgba(42, 71, 94, 0.78);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .chat-bubble::after {
            content: "";
            position: absolute;
            top: 0;
            width: 12px;
            height: 12px;
        }

        .chat-bubble-in {
            border-top-left-radius: 6px;
            background: #0f1923;
            color: #e5ecf5;
        }

        .chat-bubble-in::after {
            left: -7px;
            background: linear-gradient(225deg, #0f1923 0 50%, transparent 50% 100%);
        }

        .chat-bubble-out {
            border-top-right-radius: 6px;
            border-color: rgba(102, 192, 244, 0.5);
            background: linear-gradient(135deg, #118dff, #2d73ff);
            color: #ffffff;
        }

        .chat-bubble-out::after {
            right: -7px;
            background: linear-gradient(135deg, #118dff 0 50%, transparent 50% 100%);
        }

        .chat-date-chip {
            border: 1px solid rgba(102, 192, 244, 0.18);
            background: rgba(15, 25, 35, 0.92);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.24);
        }
    </style>
@endpush

@section('content')
    <div class="py-10">
        <div class="mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[360px_1fr] lg:px-8">
            @include('chat.partials.conversation-list')

            <div class="flex min-h-[640px] flex-col overflow-hidden rounded-2xl border border-[#2a475e]/90 bg-[#0f1923]/90 shadow-2xl shadow-black/25">
                <div class="flex items-center justify-between gap-4 border-b border-[#2a475e] bg-[#07111d]/70 p-5">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] text-lg font-black text-white">
                            {{ strtoupper(substr($friend->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <h1 class="truncate text-xl font-black text-white">{{ $friend->name }}</h1>
                            <p class="truncate text-sm text-gray-400">{{ $friend->email }}</p>
                        </div>
                    </div>

                    <a href="{{ route('friends.index') }}" class="rounded-lg border border-[#2a475e] px-4 py-2 text-sm font-bold text-gray-300 transition hover:border-[#66c0f4] hover:text-white">
                        Teman
                    </a>
                </div>

                <div class="chat-thread-surface flex-1 space-y-2 overflow-y-auto p-5">
                    @php
                        $lastDateLabel = null;
                    @endphp

                    @forelse($messages as $chatMessage)
                        @php
                            $isMine = $chatMessage->sender_id === $user->id;
                            $dateLabel = $chatMessage->sentDateLabel();
                        @endphp

                        @if($dateLabel !== $lastDateLabel)
                            <div class="sticky top-3 z-10 my-4 flex justify-center">
                                <span class="chat-date-chip rounded-lg px-4 py-2 text-sm font-black text-gray-100">
                                    {{ $dateLabel }}
                                </span>
                            </div>
                            @php
                                $lastDateLabel = $dateLabel;
                            @endphp
                        @endif

                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="chat-bubble {{ $isMine ? 'chat-bubble-out' : 'chat-bubble-in' }}">
                                <span class="whitespace-pre-line break-words font-semibold">{{ $chatMessage->message }}</span>
                                <span class="ml-3 inline-flex translate-y-[1px] items-center gap-1 whitespace-nowrap text-[11px] font-bold leading-none {{ $isMine ? 'text-emerald-100/70' : 'text-gray-400' }}">
                                    <time
                                        datetime="{{ $chatMessage->created_at?->toIso8601String() }}"
                                        title="{{ $chatMessage->sentAtLabel() }}"
                                    >
                                        {{ $chatMessage->sentTimeLabel() }}
                                    </time>
                                    @if($isMine)
                                        <span class="text-[13px] leading-none text-emerald-100/70" aria-label="Terkirim">✓✓</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex h-full min-h-[360px] items-center justify-center text-center">
                            <div class="max-w-sm">
                                <h2 class="text-xl font-black text-white">Belum ada pesan</h2>
                                <p class="mt-2 text-sm leading-6 text-gray-400">
                                    Kirim pesan pertama ke {{ $friend->name }}.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <form action="{{ route('chat.store', $friend) }}" method="POST" class="border-t border-[#2a475e] bg-[#07111d]/70 p-4">
                    @csrf
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <label class="sr-only" for="body">Pesan</label>
                        <textarea
                            id="body"
                            name="body"
                            rows="2"
                            required
                            maxlength="2000"
                            placeholder="Tulis pesan..."
                            class="min-h-[52px] flex-1 resize-none rounded-xl border border-[#316282] bg-[#050a12]/70 px-4 py-3 text-white outline-none transition focus:border-[#66c0f4]"
                        >{{ old('body') }}</textarea>
                        <button type="submit" class="rounded-xl bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-6 py-3 text-sm font-black text-white shadow-lg shadow-blue-950/30 transition hover:brightness-110">
                            Kirim
                        </button>
                    </div>

                    @error('body')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
    </div>
@endsection
