<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('忘記密碼了嗎？沒問題。只需告訴我們您的電子郵件地址，我們就會透過電子郵件向您發送密碼重設鏈接，以便您可以選擇新的密碼。') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('電子郵件密碼重設鏈接') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
