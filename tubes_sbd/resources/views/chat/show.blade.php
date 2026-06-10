@extends('layouts.store')

@section('title', 'Chat dengan ' . $friend->name . ' - PlayMart')

@push('styles')
    <style>
        .chat-thread-surface {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.9), rgba(7, 17, 29, 0.94)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.1), rgba(45, 115, 255, 0.04));
            overscroll-behavior: contain;
            scrollbar-color: #2a475e #07111d;
        }

        .chat-thread-surface::-webkit-scrollbar,
        .chat-sidebar-list::-webkit-scrollbar {
            width: 10px;
        }

        .chat-thread-surface::-webkit-scrollbar-track,
        .chat-sidebar-list::-webkit-scrollbar-track {
            background: #07111d;
        }

        .chat-thread-surface::-webkit-scrollbar-thumb,
        .chat-sidebar-list::-webkit-scrollbar-thumb {
            border: 2px solid #07111d;
            border-radius: 999px;
            background: #2a475e;
        }

        .chat-thread-surface::-webkit-scrollbar-thumb:hover,
        .chat-sidebar-list::-webkit-scrollbar-thumb:hover {
            background: #3b6b8d;
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

        .chat-panel {
            height: min(760px, calc(100vh - 120px));
            min-height: 520px;
        }

        @media (min-width: 1024px) {
            .chat-layout {
                height: calc(100vh - 120px);
                min-height: 560px;
            }

            .chat-sidebar,
            .chat-panel {
                height: 100%;
                min-height: 0;
            }

            .chat-sidebar-list {
                height: calc(100% - 82px);
                max-height: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="py-6 lg:py-8">
        <div class="chat-layout mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[360px_1fr] lg:px-8">
            @include('chat.partials.conversation-list')

            <div class="chat-panel flex min-h-[560px] flex-col overflow-hidden rounded-2xl border border-[#2a475e]/90 bg-[#0f1923]/90 shadow-2xl shadow-black/25 lg:min-h-0">
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

                @php
                    $lastDateLabel = null;
                    $initialLastDateLabel = $messages->last()?->sentDateLabel() ?? '';
                @endphp

                <div
                    class="chat-thread-surface min-h-0 flex-1 space-y-2 overflow-y-auto p-5"
                    data-chat-thread
                    data-messages-url="{{ route('chat.messages', $friend) }}"
                    data-last-message-id="{{ $messages->last()?->id ?? 0 }}"
                    data-last-date-label="{{ $initialLastDateLabel }}"
                >

                    @forelse($messages as $chatMessage)
                        @php
                            $isMine = $chatMessage->sender_id === $user->id;
                            $dateLabel = $chatMessage->sentDateLabel();
                        @endphp

                        @if($dateLabel !== $lastDateLabel)
                            <div class="sticky top-3 z-10 my-4 flex justify-center">
                                <span class="chat-date-chip rounded-lg px-4 py-2 text-sm font-black text-gray-100" data-chat-date-chip>
                                    {{ $dateLabel }}
                                </span>
                            </div>
                            @php
                                $lastDateLabel = $dateLabel;
                            @endphp
                        @endif

                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" data-chat-message data-message-id="{{ $chatMessage->id }}">
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
                        <div class="flex h-full min-h-[360px] items-center justify-center text-center" data-chat-empty>
                            <div class="max-w-sm">
                                <h2 class="text-xl font-black text-white">Belum ada pesan</h2>
                                <p class="mt-2 text-sm leading-6 text-gray-400">
                                    Kirim pesan pertama ke {{ $friend->name }}.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <form action="{{ route('chat.store', $friend) }}" method="POST" class="border-t border-[#2a475e] bg-[#07111d]/70 p-4" data-chat-form>
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
                            data-chat-input
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const thread = document.querySelector('[data-chat-thread]');
            const form = document.querySelector('[data-chat-form]');
            const input = document.querySelector('[data-chat-input]');

            if (!thread) {
                return;
            }

            const scrollToBottom = () => {
                thread.scrollTop = thread.scrollHeight;
            };

            const isNearBottom = () => thread.scrollHeight - thread.scrollTop - thread.clientHeight < 160;

            const appendDateChip = (label) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'sticky top-3 z-10 my-4 flex justify-center';

                const chip = document.createElement('span');
                chip.className = 'chat-date-chip rounded-lg px-4 py-2 text-sm font-black text-gray-100';
                chip.dataset.chatDateChip = '';
                chip.textContent = label;

                wrapper.append(chip);
                thread.append(wrapper);
            };

            const appendMessage = (message) => {
                if (!message || thread.querySelector(`[data-message-id="${message.id}"]`)) {
                    return false;
                }

                const emptyState = thread.querySelector('[data-chat-empty]');
                if (emptyState) {
                    emptyState.remove();
                }

                if (message.date_label && message.date_label !== thread.dataset.lastDateLabel) {
                    appendDateChip(message.date_label);
                    thread.dataset.lastDateLabel = message.date_label;
                }

                const row = document.createElement('div');
                row.className = `flex ${message.is_mine ? 'justify-end' : 'justify-start'}`;
                row.dataset.chatMessage = '';
                row.dataset.messageId = message.id;

                const bubble = document.createElement('div');
                bubble.className = `chat-bubble ${message.is_mine ? 'chat-bubble-out' : 'chat-bubble-in'}`;

                const body = document.createElement('span');
                body.className = 'whitespace-pre-line break-words font-semibold';
                body.textContent = message.body;

                const meta = document.createElement('span');
                meta.className = `ml-3 inline-flex translate-y-[1px] items-center gap-1 whitespace-nowrap text-[11px] font-bold leading-none ${message.is_mine ? 'text-emerald-100/70' : 'text-gray-400'}`;

                const time = document.createElement('time');
                time.dateTime = message.created_at || '';
                time.title = message.sent_at_label || '';
                time.textContent = message.time_label || '';
                meta.append(time);

                if (message.is_mine) {
                    const ticks = document.createElement('span');
                    ticks.className = 'text-[13px] leading-none text-emerald-100/70';
                    ticks.setAttribute('aria-label', 'Terkirim');
                    ticks.textContent = '✓✓';
                    meta.append(ticks);
                }

                bubble.append(body, meta);
                row.append(bubble);
                thread.append(row);
                thread.dataset.lastMessageId = Math.max(Number(thread.dataset.lastMessageId || 0), Number(message.id || 0));

                return true;
            };

            const pollMessages = async () => {
                if (thread.dataset.polling === '1') {
                    return;
                }

                thread.dataset.polling = '1';

                try {
                    const url = new URL(thread.dataset.messagesUrl, window.location.origin);
                    url.searchParams.set('after_id', thread.dataset.lastMessageId || '0');

                    const response = await fetch(url, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();
                    const shouldScroll = isNearBottom();
                    let added = false;

                    (data.messages || []).forEach((message) => {
                        if (appendMessage(message)) {
                            added = true;
                        }
                    });

                    if (data.last_id) {
                        thread.dataset.lastMessageId = Math.max(Number(thread.dataset.lastMessageId || 0), Number(data.last_id));
                    }

                    if (added && shouldScroll) {
                        scrollToBottom();
                    }
                } finally {
                    thread.dataset.polling = '0';
                }
            };

            scrollToBottom();
            setInterval(pollMessages, 2000);
            window.addEventListener('focus', pollMessages);

            form?.addEventListener('submit', async (event) => {
                const body = input?.value.trim();

                if (!body) {
                    return;
                }

                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');

                try {
                    submitButton?.setAttribute('disabled', 'disabled');

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    if (!response.ok) {
                        form.submit();
                        return;
                    }

                    const data = await response.json();
                    appendMessage(data.message);
                    input.value = '';
                    input.focus();
                    scrollToBottom();
                } catch (error) {
                    form.submit();
                } finally {
                    submitButton?.removeAttribute('disabled');
                }
            });
        });
    </script>
@endpush
