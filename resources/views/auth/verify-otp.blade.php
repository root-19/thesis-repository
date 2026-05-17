<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-[#252422]">Verify Your Email</h2>
        <p class="text-sm text-[#CCC5B9] mt-1">Enter the 6-digit code sent to <span class="text-[#403D39] font-medium">{{ $email }}</span></p>
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-[#2b8c62]">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.otp.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <x-input-label for="otp" :value="__('Verification Code')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center text-2xl tracking-[0.5em] font-bold" type="text" name="otp" required autofocus maxlength="6" placeholder="------" inputmode="numeric" pattern="[0-9]{6}" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('verification.otp.resend') }}" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="text-sm text-[#CCC5B9] hover:text-[#EB5E28] transition-colors font-medium">
                {{ __('Resend Code') }}
            </a>

            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <form id="resend-form" method="POST" action="{{ route('verification.otp.resend') }}" class="hidden">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
    </form>
</x-guest-layout>
