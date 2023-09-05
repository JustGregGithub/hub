<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><a href="{{ route('staff.settings.servers') }}" class="text-gray-300">{{ $server->name }}</a> / Players</h1>
        <span id="quote" class="text-gray-400 mt-2">View all the players who joined {{ $server->name }}.</span>
        <div class="mt-5">
            <livewire:player-table :server="$server" />
            <p class="text-gray-400 text-sm" id="countdown"></p>
        </div>
    </div>
    <script>
        const seconds = Math.floor({{ \App\Http\Livewire\PlayerTable::REFRESH_TIME }} / 1000);
        let countdown = seconds;

        // Function to update the countdown element
        function updateCountdown() {
            const countdownElement = document.getElementById("countdown");

            if (countdown > 1) {
                countdownElement.textContent = 'Refreshing in ' + countdown + ' seconds';
                countdown--;
            } else if (countdown === 1) {
                countdownElement.textContent = 'Refreshing in ' + countdown + ' seconds';
                countdown = seconds;
            }
        }

        // Update the countdown every second
        setInterval(updateCountdown, 1000);

        // Initial update
        updateCountdown();
    </script>
</x-app-layout>

