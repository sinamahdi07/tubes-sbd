{{-- ===== GLOBAL ALPINE CONFIRM MODAL ===== --}}
<div
    x-data="adminConfirmModal()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    @keydown.escape.window="cancel()"
    style="display:none"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/70 backdrop-blur-sm"
        @click="cancel()"
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="relative w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl"
        style="background: rgba(10,18,28,0.98); border: 1px solid rgba(255,255,255,0.08);"
    >
        {{-- Color accent bar top --}}
        <div class="h-1 w-full" :class="{
            'bg-gradient-to-r from-red-600 to-red-400': type === 'danger',
            'bg-gradient-to-r from-amber-500 to-yellow-400': type === 'warning',
            'bg-gradient-to-r from-blue-600 to-cyan-400': type === 'info'
        }"></div>

        <div class="p-6">
            {{-- Icon --}}
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center" :class="{
                    'bg-red-500/15': type === 'danger',
                    'bg-amber-500/15': type === 'warning',
                    'bg-blue-500/15': type === 'info'
                }">
                    {{-- Danger icon --}}
                    <svg x-show="type === 'danger'" class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    {{-- Warning icon --}}
                    <svg x-show="type === 'warning'" class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    {{-- Info icon --}}
                    <svg x-show="type === 'info'" class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-black text-white" x-text="title"></h3>
                    <p class="text-sm text-gray-400 mt-1 leading-relaxed" x-text="message"></p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 mt-6">
                <button
                    @click="cancel()"
                    class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                           bg-white/5 text-gray-400 hover:text-white border border-white/10
                           hover:border-white/20 transition-all focus:outline-none focus:ring-2 focus:ring-white/20"
                >
                    Batal
                </button>
                <button
                    @click="confirm()"
                    class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                           text-white transition-all focus:outline-none focus:ring-2"
                    :class="{
                        'bg-red-600 hover:bg-red-500 shadow-lg shadow-red-500/25 focus:ring-red-500/40': type === 'danger',
                        'bg-amber-500 hover:bg-amber-400 shadow-lg shadow-amber-500/25 focus:ring-amber-500/40': type === 'warning',
                        'bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-500/25 focus:ring-blue-500/40': type === 'info'
                    }"
                    x-text="confirmLabel"
                ></button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global Alpine Confirm Modal Component
    if (!window.adminConfirmModal) {
        window.adminConfirmModal = function() {
            return {
                open: false,
                title: 'Konfirmasi',
                message: 'Apakah Anda yakin?',
                type: 'danger',
                confirmLabel: 'Ya, Lakukan',
                confirmCallback: null,
                cancelCallback: null,

                init() {
                    window.adminConfirm = (options) => {
                        this.title = options.title || 'Konfirmasi';
                        this.message = options.message || 'Apakah Anda yakin?';
                        this.type = options.type || 'danger';
                        this.confirmLabel = options.confirmLabel || (this.type === 'danger' ? 'Ya, Hapus' : 'Ya, Lakukan');
                        this.confirmCallback = options.onConfirm || null;
                        this.cancelCallback = options.onCancel || null;
                        this.open = true;
                    };
                },

                confirm() {
                    if (this.confirmCallback) {
                        this.confirmCallback();
                    }
                    this.open = false;
                },

                cancel() {
                    if (this.cancelCallback) {
                        this.cancelCallback();
                    }
                    this.open = false;
                }
            }
        };

        // Helper to submit forms using Alpine confirm modal
        window.adminConfirmSubmit = function(event, message, type = 'danger', title = 'Konfirmasi') {
            event.preventDefault();
            const form = event.target.closest('form');
            window.adminConfirm({
                title: title,
                message: message,
                type: type,
                onConfirm: () => {
                    form.submit();
                }
            });
            return false;
        };
    }
</script>
