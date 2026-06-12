@extends('layouts.store')

@section('title', 'Chat - PlayMart')

@push('styles')
    <style>
        .chat-container-main {
            background: radial-gradient(circle at top right, rgba(17, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(45, 115, 255, 0.05), transparent 40%);
        }
        .chat-layout {
            height: calc(100vh - 160px);
            min-height: 600px;
        }
        .empty-chat-surface {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .pulse-icon {
            animation: pulse-blue 2s infinite;
        }
        @keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(102, 192, 244, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(102, 192, 244, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 192, 244, 0); }
        }
    </style>
@endpush

@section('content')
    <div class="chat-container-main min-h-screen py-6 lg:py-10">
        <div class="chat-layout mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[380px_1fr] lg:px-8">
            @include('chat.partials.conversation-list')

            <div class="empty-chat-surface flex flex-col items-center justify-center rounded-[2rem] p-12 text-center shadow-2xl transition-all duration-500 hover:bg-opacity-30">
                <div class="relative mb-8">
                    <div class="pulse-icon flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-tr from-[#118dff] to-[#66c0f4] text-white shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                        </svg>
                    </div>
                    <div class="absolute -bottom-2 -right-2 h-8 w-8 rounded-full border-4 border-[#07111d] bg-emerald-400"></div>
                </div>

                <h1 class="text-4xl font-black tracking-tighter text-white lg:text-5xl">Your Hub for <br><span class="text-[#66c0f4]">Gaming Talk.</span></h1>
                
                <p class="mx-auto mt-6 max-w-sm text-lg font-medium leading-relaxed text-gray-400">
                    Terhubunglah dengan teman-teman gamer-mu. Kirim strategi, janjian mabar, atau sekadar berbagi momen epik!
                </p>

                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('friends.index') }}" class="group relative inline-flex items-center gap-3 overflow-hidden rounded-2xl bg-white px-8 py-4 text-base font-black text-black transition-all hover:scale-105 active:scale-95">
                        <span>Kelola Teman</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="mt-16 grid grid-cols-3 gap-8 opacity-20">
                    <div class="flex flex-col items-center gap-2">
                        <div class="h-1 w-12 rounded-full bg-white"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Encrypted</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="h-1 w-12 rounded-full bg-white"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Instant</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="h-1 w-12 rounded-full bg-white"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Private</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
