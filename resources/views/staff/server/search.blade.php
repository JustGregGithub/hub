<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><a href="{{ route('staff.settings.servers') }}" class="text-gray-300">{{ $server->name }}</a> / Quick Search</h1>
        <span id="quote" class="text-gray-400 mt-2">Quickly Search for a player using their in-game ID in {{ $server->name }}.</span>
        <div class="mt-5">
            <livewire:quick-search :server="$server" />
        </div>
    </div>
</x-app-layout>

