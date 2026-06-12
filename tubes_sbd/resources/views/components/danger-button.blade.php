<button {{ $attributes->merge(['type' => 'submit', 'class' => 'steam-btn-danger text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
