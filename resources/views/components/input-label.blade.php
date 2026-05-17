@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#403D39]']) }}>
    {{ $value ?? $slot }}
</label>
