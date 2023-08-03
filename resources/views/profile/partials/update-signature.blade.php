<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Signature') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your signature to be used across the hub.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <form method="POST" action="{{ route('profile.signature.post') }}">
            @csrf
            <x-input-label for="signature" :value="__('Signature')" />
            <x-text-input id="signature" name="signature" type="text" class="mt-1 block w-full" :value="$user->signature" maxlength="150" />
            <input type="submit" value="Update Signature" class="bg-green-600 hover:bg-green-500 cursor-pointer transition text-white px-4 py-2 text-sm uppercase font-semibold rounded-md font-thin mt-2">
        </form>
    </div>
</section>
