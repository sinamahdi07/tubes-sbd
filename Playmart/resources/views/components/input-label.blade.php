@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-slate-300']) }}>
    {{ $value ?? $slot }}
</label>
