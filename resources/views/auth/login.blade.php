<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground">Log in</h1>
        <p class="text-sm text-muted-foreground mt-1">Welcome back, enter your details.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-muted-foreground hover:text-foreground transition-colors">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-input text-primary focus:ring-ring focus:ring-offset-0">
            <span class="text-sm text-muted-foreground">{{ __('Remember me') }}</span>
        </label>

        <x-primary-button class="w-full justify-center">
            {{ __('Log in') }}
        </x-primary-button>

        <p class="text-center text-sm text-muted-foreground">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-foreground font-medium hover:underline">{{ __('Sign up') }}</a>
        </p>
    </form>
</x-guest-layout>
