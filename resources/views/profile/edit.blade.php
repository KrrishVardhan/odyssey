<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 py-8 space-y-6">

        <h1 class="text-lg font-semibold text-foreground">
            Account settings
        </h1>

        {{-- Avatar --}}
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-sm font-medium text-foreground mb-3">
                Avatar
            </p>

            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data"
                class="flex items-center gap-4">
                @csrf

                <div
                    class="w-16 h-16 rounded-full bg-secondary flex items-center justify-center overflow-hidden shrink-0">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-mono font-semibold text-secondary-foreground">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    @endif
                </div>

                <div>
                    <input type="file" name="avatar" accept="image/*" onchange="this.form.submit()"
                        class="text-sm text-muted-foreground file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-border file:bg-muted file:text-foreground file:text-sm hover:file:bg-secondary">
                    <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                </div>
            </form>
        </div>

        {{-- Username --}}
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-sm font-medium text-foreground mb-3">
                Username
            </p>

            <form method="POST" action="{{ route('profile.username') }}" class="flex gap-3">
                @csrf
                @method('PATCH')

                <x-text-input name="name" type="text" class="flex-1" value="{{ old('name', $user->name) }}"
                    required />
                <x-primary-button>Save</x-primary-button>
            </form>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email — read only --}}
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-sm font-medium text-foreground mb-1">
                Email
            </p>
            <p class="text-sm text-muted-foreground">
                {{ $user->email }}
            </p>
            <p class="text-xs text-muted-foreground mt-1">
                Email cannot be changed.
            </p>
        </div>

        {{-- Password --}}
        @if (!$user->google_id)
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm font-medium text-foreground mb-3">
                    Password
                </p>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <x-text-input name="current_password" type="password" class="block w-full"
                        placeholder="Current password" required />
                    <x-input-error :messages="$errors->get('current_password')" class="mt-1" />

                    <x-text-input name="password" type="password" class="block w-full" placeholder="New password"
                        required />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />

                    <x-text-input name="password_confirmation" type="password" class="block w-full"
                        placeholder="Confirm new password" required />

                    <x-primary-button>Update password</x-primary-button>
                </form>
            </div>
        @else
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm font-medium text-foreground mb-1">
                    Password
                </p>
                <p class="text-sm text-muted-foreground">
                    You're signed in with Google. Password login is not available for this account.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
