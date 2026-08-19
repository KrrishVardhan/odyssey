<section>
    <header>
        <h2 class="text-lg font-medium text-primary">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-muted-foreground">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Profile Picture --}}
        <div class="space-y-3">
            <x-input-label :value="__('Profile Picture')" />

            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="relative group">
                    <div
                        class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-muted ring-1 ring-border">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                class="h-full w-full object-cover" id="profile-photo-preview">
                        @else
                            <span id="profile-photo-initials" class="text-2xl font-medium text-muted-foreground">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Camera button --}}
                    <label for="profile_photo"
                        class="absolute bottom-0 right-0 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border-2 border-background bg-primary text-primary-foreground shadow-sm transition hover:scale-105 hover:opacity-90"
                        title="{{ __('Change profile picture') }}">
                        <x-lucide-camera class="h-4 w-4" />
                    </label>

                    <input id="profile_photo" name="profile_photo" type="file"
                        accept="image/png,image/jpeg,image/webp" class="sr-only">
                </div>

                {{-- Description --}}
                <div class="space-y-1">
                    <p class="text-sm font-medium text-primary">
                        {{ __('Your profile picture') }}
                    </p>

                    <p class="text-sm text-muted-foreground">
                        {{ __('Choose a clear image that represents you.') }}
                    </p>

                    <p class="text-xs text-muted-foreground">
                        {{ __('PNG, JPG or WebP. Max 5MB.') }}
                    </p>

                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />

            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Save --}}
        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
