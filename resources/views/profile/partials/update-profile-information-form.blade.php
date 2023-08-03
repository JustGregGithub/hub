<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @if (Auth::user()->global_name)
            <div>
                <x-input-label for="global_name" :value="__('Display Name')" />
                <x-text-input id="global_name" name="global_name" type="text" class="mt-1 block w-full bg-gray-50" :value="old('global_name', $user->global_name)" required autocomplete="global_name" disabled />
                <x-input-error class="mt-2" :messages="$errors->get('global_name')" for="global_name" />
                <small class="text-gray-500">You cannot edit this via the hub.</small>
            </div>
        @endif
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username . '#' . $user->discriminator)" required autocomplete="username" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('username')" for="username" />
            <small class="text-gray-500">You cannot edit this via the hub.</small>
        </div>
    </div>
</section>
