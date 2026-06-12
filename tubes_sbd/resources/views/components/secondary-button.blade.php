<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary text-xs uppercase tracking-widest disabled:opacity-25']) }}>
    {{ $slot }}
</button>
