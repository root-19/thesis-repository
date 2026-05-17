@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#2b8c62]']) }}>
        {{ $status }}
    </div>
@endif
