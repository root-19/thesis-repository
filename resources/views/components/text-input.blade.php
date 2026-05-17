@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-[#CCC5B9]/40 focus:border-[#EB5E28] focus:ring-[#EB5E28] rounded-xl shadow-sm bg-[#FFFCF2]/50 placeholder-[#CCC5B9]']) !!}>
