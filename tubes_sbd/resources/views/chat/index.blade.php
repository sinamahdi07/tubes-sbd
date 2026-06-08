@extends('layouts.store')

@section('title', 'Chat - PlayMart')

@section('content')
    <div class="py-10">
        <div class="mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[360px_1fr] lg:px-8">
            @include('chat.partials.conversation-list')

            <div class="flex min-h-[560px] items-center justify-center rounded-2xl border border-[#2a475e]/90 bg-[#0f1923]/90 p-8 text-center shadow-2xl shadow-black/25">
                <div class="max-w-md">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-[#2a475e] bg-[#07111d] text-[#66c0f4]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                        </svg>
                    </div>
                    <h1 class="mt-5 text-2xl font-black text-white">Pilih teman untuk mulai chat</h1>
                    <p class="mt-3 text-sm leading-6 text-gray-400">
                        Semua percakapan hanya bisa dilakukan dengan teman yang sudah saling menerima permintaan.
                    </p>
                    <a href="{{ route('friends.index') }}" class="mt-6 inline-flex rounded-xl bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-950/30 transition hover:brightness-110">
                        Kelola Teman
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
