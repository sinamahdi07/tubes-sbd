@extends('layouts.store')

@section('title', 'Chat with ' . $friend->name . ' - PlayMart')

@push('styles')
    <style>
        .chat-container-main {
            background: radial-gradient(circle at top right, rgba(17, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(45, 115, 255, 0.05), transparent 40%);
        }
        .chat-thread-surface {
            background: rgba(0, 0, 0, 0.2);
            overscroll-behavior: contain;
        }

        .chat-bubble {
            position: relative;
            max-width: min(85%, 600px);
            padding: 12px 16px;
            font-size: 15px;
            line-height: 1.5;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .chat-bubble-in {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: #fff;
            border-radius: 4px 20px 20px 20px;
        }

        .chat-bubble-out {
            background: linear-gradient(135deg, #118dff, #2d73ff);
            color: #ffffff;
            border-radius: 20px 20px 4px 20px;
            box-shadow: 0 10px 20px rgba(17, 141, 255, 0.2);
        }

        .chat-date-chip {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 900;
        }

        .chat-panel {
            height: calc(100vh - 160px);
            min-height: 600px;
            background: rgba(15, 25, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (min-width: 1024px) {
            .chat-layout {
                height: calc(100vh - 160px);
                min-height: 600px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="chat-container-main min-h-screen py-6 lg:py-10">
        <div class="chat-layout mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[380px_1fr] lg:px-8">
            @include('chat.partials.conversation-list')

            <div class="chat-panel flex flex-col overflow-hidden rounded-[2rem] shadow-2xl">
                <!-- Chat Header -->
                <div class="flex items-center justify-between gap-4 border-b border-white/5 bg-white/5 p-6 backdrop-blur-md">
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="relative">
                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-[#07111d] to-[#16202d] text-xl font-black text-white shadow-lg">
                                {{ strtoupper(substr($friend->name, 0, 1)) }}
                            </span>
                            <div class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border-2 border-[#0f1923] bg-success"></div>
                        </div>
                        <div class="min-w-0">
                            <h1 class="truncate text-xl font-black tracking-tighter text-white">{{ $friend->name }}</h1>
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full bg-success"></div>
                                <span class="text-xs font-bold text-success uppercase tracking-widest">Online</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </button>
                        <button class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>

                @php
                    $lastDateLabel = null;
                    $initialLastDateLabel = $messages->last()?->sentDateLabel() ?? '';
                @endphp

                <div
                    class="chat-thread-surface min-h-0 flex-1 space-y-6 overflow-y-auto p-6 store-scrollbar"
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
                            <div class="sticky top-0 z-10 flex justify-center py-4">
                                <span class="chat-date-chip rounded-full px-6 py-2 shadow-xl" data-chat-date-chip>
                                    {{ $dateLabel }}
                                </span>
                            </div>
                            @php
                                $lastDateLabel = $dateLabel;
                            @endphp
                        @endif

                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} animate-in fade-in slide-in-from-bottom-2 duration-300" data-chat-message data-message-id="{{ $chatMessage->id }}">
                            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} gap-1">
                                <div class="chat-bubble {{ $isMine ? 'chat-bubble-out' : 'chat-bubble-in' }}">
                                    <p class="whitespace-pre-line break-words font-medium leading-relaxed">{{ $chatMessage->message }}</p>
                                </div>
                                <div class="flex items-center gap-2 px-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-white/20">
                                        {{ $chatMessage->sentTimeLabel() }}
                                    </span>
                                    @if($isMine)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#66c0f4]" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex h-full flex-col items-center justify-center text-center p-12" data-chat-empty>
                            <div class="mb-6 h-20 w-20 rounded-3xl bg-white/5 flex items-center justify-center text-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-white tracking-tighter">Start the conversation</h2>
                            <p class="mt-3 max-w-xs text-sm font-medium leading-relaxed text-white/40">
                                Kirim pesan pertama ke {{ $friend->name }} dan mulailah merencanakan mabar berikutnya!
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <form action="{{ route('chat.store', $friend) }}" method="POST" class="bg-white/5 p-6 backdrop-blur-xl border-t border-white/5" data-chat-form>
                    @csrf
                    <div class="flex items-end gap-4">
                        <div class="flex-1 relative group">
                            <textarea
                                id="body"
                                name="body"
                                rows="1"
                                required
                                maxlength="2000"
                                placeholder="Write a message..."
                                class="w-full min-h-[56px] max-h-32 resize-none rounded-2xl bg-black/40 border-white/5 px-5 py-4 text-sm text-white placeholder-white/20 focus:ring-1 focus:ring-[#66c0f4]/50 focus:border-transparent transition-all store-scrollbar"
                                data-chat-input
                            >{{ old('body') }}</textarea>
                            <div class="absolute right-4 bottom-4 flex items-center gap-2 opacity-0 group-focus-within:opacity-100 transition-opacity">
                                <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">Enter to send</span>
                            </div>
                        </div>
                        <button type="submit" class="h-[56px] w-[56px] shrink-0 rounded-2xl bg-gradient-to-tr from-[#118dff] to-[#66c0f4] flex items-center justify-center text-white shadow-xl shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
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

            if (!thread) return;

            const scrollToBottom = () => {
                thread.scrollTo({ top: thread.scrollHeight, behavior: 'smooth' });
            };

            const isNearBottom = () => thread.scrollHeight - thread.scrollTop - thread.clientHeight < 200;

            const appendDateChip = (label) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'sticky top-0 z-10 flex justify-center py-4';
                wrapper.innerHTML = `<span class="chat-date-chip rounded-full px-6 py-2 shadow-xl">${label}</span>`;
                thread.append(wrapper);
            };

            const appendMessage = (message) => {
                if (!message || thread.querySelector(`[data-message-id="${message.id}"]`)) return false;

                const emptyState = thread.querySelector('[data-chat-empty]');
                if (emptyState) emptyState.remove();

                if (message.date_label && message.date_label !== thread.dataset.lastDateLabel) {
                    appendDateChip(message.date_label);
                    thread.dataset.lastDateLabel = message.date_label;
                }

                const isMine = message.is_mine;
                const row = document.createElement('div');
                row.className = `flex ${isMine ? 'justify-end' : 'justify-start'} animate-in fade-in slide-in-from-bottom-2 duration-300`;
                row.dataset.chatMessage = '';
                row.dataset.messageId = message.id;

                row.innerHTML = `
                    <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'} gap-1">
                        <div class="chat-bubble ${isMine ? 'chat-bubble-out' : 'chat-bubble-in'}">
                            <p class="whitespace-pre-line break-words font-medium leading-relaxed">${message.body}</p>
                        </div>
                        <div class="flex items-center gap-2 px-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/20">${message.time_label}</span>
                            ${isMine ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#66c0f4]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' : ''}
                        </div>
                    </div>
                `;

                thread.append(row);
                thread.dataset.lastMessageId = Math.max(Number(thread.dataset.lastMessageId || 0), Number(message.id || 0));
                return true;
            };

            const pollMessages = async () => {
                if (thread.dataset.polling === '1') return;
                thread.dataset.polling = '1';

                try {
                    const url = new URL(thread.dataset.messagesUrl, window.location.origin);
                    url.searchParams.set('after_id', thread.dataset.lastMessageId || '0');
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});

                    if (response.ok) {
                        const data = await response.json();
                        const shouldScroll = isNearBottom();
                        let added = false;
                        (data.messages || []).forEach(msg => { if (appendMessage(msg)) added = true; });
                        if (added && shouldScroll) scrollToBottom();
                    }
                } finally {
                    thread.dataset.polling = '0';
                }
            };

            scrollToBottom();
            setInterval(pollMessages, 3000);

            form?.addEventListener('submit', async (e) => {
                const body = input?.value.trim();
                if (!body) return;
                e.preventDefault();

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form),
                    });

                    if (response.ok) {
                        const data = await response.json();
                        appendMessage(data.message);
                        input.value = '';
                        scrollToBottom();
                    }
                } catch (error) {
                    console.error('Send error:', error);
                }
            });

            input?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey && window.innerWidth > 768) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
@endpush

