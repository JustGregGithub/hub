@php
    $playerStatistics = $server->player_count;
    $latestTimestamps = array_keys(array_slice($playerStatistics, -10, null, true));
    $labels = collect($latestTimestamps)->map(function ($timestamp) {
        return date('H:i', strtotime($timestamp));
    });

    $data = collect($latestTimestamps)->map(function ($timestamp) use ($playerStatistics) {
        return $playerStatistics[$timestamp];
    });

    $user_statistics = Auth::user()->records()->where('server_id', $server->id)->whereDate('created_at', Carbon\Carbon::today())->get();
@endphp

<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><span id="timeMessage" class="font-normal"></span>, {{ Auth::user()->displayName() }}!</h1>
        <span id="quote" class="text-gray-400 animate-pulse bg-gray-500 rounded-md block w-1/4 mt-2">.</span>
        <div class="flex flex-col gap-4 mt-10 2xl:flex-row">
            <div class="rounded-lg 2xl:w-4/6">
                <div class="grid grid-cols-1 ld:grid-cols-2 xl:grid-cols-3 gap-4 h-fit">
                    <div class="bg-zinc-800 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Your Role</p>
                        <p class="text-gray-400">The highest role you have.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">
                            {{ Auth::user()->getHighestRole($server)->name }}
                        </p>
                    </div>
                    <div class="bg-zinc-800 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Total Punishments</p>
                        <p class="text-gray-400">The amount of punishments in total.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->records()->count() }}</p>
                    </div>
                    <div class="bg-zinc-800 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Server Status</p>
                        <p class="text-gray-400">The current server's status.</p>
                        @if($server->isOnline())
                            <p class="text-3xl text-green-400 font-extrabold mt-5">Online</p>
                        @else
                            <p class="text-3xl text-red-400 font-extrabold mt-5">Offline</p>
                        @endif
                    </div>
                </div>
                <div class="bg-zinc-800 p-6 rounded-lg mt-4 text-white">
                    <p class="text-white font-extrabold text-2xl">Player Chart</p>
                    <p class="text-gray-400">The amount of players joined today.</p>
                    <canvas id="players"></canvas>

                </div>
            </div>
            <div class="2xl:w-2/6 flex flex-col gap-4">
                <div class="bg-zinc-800 p-6 rounded-lg h-fit">
                    <p class="text-white font-extrabold text-2xl">Your Statistics</p>
                    <p class="text-gray-400">The amount of punishments issued today.</p>
                    @if($user_statistics->count() === 0)
                        <p class="text-gray-400 text-center mt-5">You have not issued any punishments today.</p>
                    @else
                        <div class="flex justify-center mt-5">
                            <div class="relative flex justify-center">
                                <canvas id="statistics" width="200"></canvas>
                            </div>
                        </div>
                    @endif
                </div>

                @php
                    $totalMinutes = 0;
                    $timeclock_requirements = Auth::user()->getHighestRole($server)->timeclock_requirements;
                    $timeclock = Auth::user()->timeclocks()
                        ->where('server_id', $server->id)
                        ->where('created_at', '>=', Carbon\Carbon::now()->startOfWeek())
                        ->where('type', \App\Models\Staff\ServerTimeclock::TYPES['clock_out'])
                        ->get();

                    foreach ($timeclock as $clockIn) {
                        $totalMinutes += $clockIn->time;
                    }

                    $totalMinutes = 2;
                    $totalHours = floor($totalMinutes / 60); // Calculate whole hours
                    $percentage = $totalMinutes % 60; // Calculate remaining minutes

                    if ($percentage > 100) {
                        $percentage = 100;
                    }
                @endphp
                <div class="relative bg-purple-800 p-6 rounded-lg h-fit">
                    <p class="text-white font-extrabold text-2xl">Promotion Goal</p>
                    <p class="text-gray-400">Track your hours for a promotion!</p>

                    <div class="flex items-baseline gap-4">
                        <p class="text-white font-bold" title="{{ $totalMinutes }} Minutes">{{ $percentage }}%</p>
                        <div class="w-2/5 bg-zinc-800 rounded-full h-2.5 mt-5">
                            <div class="bg-purple-400 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    @switch($percentage)
                        @case($percentage < 25)
                            <p class="text-gray-400 mt-5 w-3/5">{{ $totalHours }} hours completed. You need {{ $timeclock_requirements - $totalHours }} more hours to be promoted.</p>
                            @break
                        @case($percentage < 50)
                            <p class="text-gray-400 mt-5 w-3/5">{{ $totalHours }} hours completed. Your half way there!</p>
                            @break
                        @case($percentage < 75)
                            <p class="text-gray-400 mt-5 w-3/5">{{ $totalHours }} hours completed. You are almost there!</p>
                            @break
                        @case($percentage == 100)
                            <p class="text-gray-400 mt-5 w-3/5">{{ $totalHours }} hours completed. Good luck on the promotion!</p>
                            @break
                    @endswitch

                    <img src="/images/medal.webp" alt="Medal 3D Render" class="absolute block right-0 top-0 object-cover h-24 md:h-48">
                </div>
            </div>
        </div>

        <script>
            const curHr = new Date().getHours()

            if (curHr < 12) {
                document.getElementById('timeMessage').innerHTML = 'Good morning'
            } else if (curHr < 18) {
                document.getElementById('timeMessage').innerHTML = 'Good afternoon'
            } else {
                document.getElementById('timeMessage').innerHTML = 'Good evening'
            }

            async function fetchQuote() {
                try {
                    const response = await fetch('{{ route('api.quote') }}');

                    if (response.ok) {
                        const data = await response.json();
                        const quoteElement = document.getElementById('quote');
                        quoteElement.innerHTML = data.quote;
                        quoteElement.classList.remove('animate-pulse', 'bg-gray-500', 'w-1/4', 'mt-2');
                    } else {
                        throw new Error('Failed to fetch data');
                    }
                } catch (err) {
                    const quoteElement = document.getElementById('quote');
                    quoteElement.classList.remove('animate-pulse', 'bg-gray-500', 'w-1/4', 'mt-2');
                    quoteElement.innerHTML = 'The best way to predict the future is to create it. - Abraham Lincoln';
                }
            }

            fetchQuote();
        </script>

        <script>
            new Chart(document.getElementById('statistics'), {
                type: 'doughnut',
                data: {
                    labels: ['Warns', 'Kicks', 'Bans', 'Commends', 'Notes'],
                    datasets: [{
                        data: [{{ $user_statistics->where('type', \App\Models\Staff\PlayerRecord::TYPES['Warn'])->count() }}, {{ $user_statistics->where('type', \App\Models\Staff\PlayerRecord::TYPES['Kick'])->count() }}, {{ $user_statistics->where('type', \App\Models\Staff\PlayerRecord::TYPES['Ban'])->count() }}, {{ $user_statistics->where('type', \App\Models\Staff\PlayerRecord::TYPES['Commend'])->count() }}, {{ $user_statistics->where('type', \App\Models\Staff\PlayerRecord::TYPES['Note'])->count() }}],
                        backgroundColor: [
                            'rgb(171,147,249)',
                            'rgb(142,117,224)',
                            'rgb(107,83,187)',
                            'rgb(89,69,155)',
                            'rgb(73,55,134)',
                        ],
                        hoverOffset: 4,
                        borderWidth: 0 // Set border width to 0 to remove the white border
                    }]
                },
                options: {
                    responsive: true, // Enable responsiveness
                    maintainAspectRatio: true, // Allow the chart to scale based on canvas dimensions
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        }
                    }
                }
            });

            new Chart(document.getElementById('players'), {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Player Count',
                        data: @json($data), // Replace with actual player count data
                        lineTension: .5,
                        backgroundColor: [
                            'rgb(84,33,181, .3)',
                        ],
                        borderColor: 'rgb(142, 117, 224)',
                        fill: true
                    }]
                },
                options: {
                    scale: {
                        ticks: {
                            precision: 0
                        }
                    }
                }
            });
        </script>
    </div>
</x-app-layout>

