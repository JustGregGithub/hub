<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold">Manage Timeclock</h1>
        <span id="quote" class="text-gray-400 mt-2">View and Manage Staff Timeclocks in {{ $server->name }}.</span>
        <div class="flex flex-col lg:flex-row gap-4 mt-4">
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-4">
                <div class="bg-zinc-800 p-6 rounded-lg">
                    <p class="text-white font-extrabold text-xl">Total Clocked Hours</p>
                    <p class="text-gray-400">The amount of hours clocked from everyone this week.</p>
                    @php
                        $minutes = $server->timeclocks()
                            ->whereIn('type', [\App\Models\Staff\ServerTimeclock::TYPES['clock_out'], \App\Models\Staff\ServerTimeclock::TYPES['auto_clock_out']])
                            ->sum('time');
                        $formattedTotalHours = \Carbon\CarbonInterval::minutes($minutes)
                            ->cascade()
                            ->format('%d Days, %h Hours, %i Minutes');
                    @endphp
                    <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $formattedTotalHours }}</p>
                </div>
                <div class="bg-zinc-800 p-6 rounded-lg">
                    <p class="text-white font-extrabold text-xl">Most Clocked Hours</p>
                    <p class="text-gray-400">Who clocked the most this week.</p>
                    @php
                        $top_user = $server->getTopTimeclock()[0]['user'];
                        $top_hours = \Carbon\CarbonInterval::minutes($server->getTopTimeclock()[0]['time'])
                            ->cascade()
                            ->format('%d Days, %h Hours, %i Minutes');
                    @endphp
                    <div class="flex items-center mt-5 gap-3">
                        <img class="h-8 w-8 rounded-full object-cover mr-2"
                             src="https://cdn.discordapp.com/avatars/{{ $top_user->id }}/{{ $top_user->avatar }}.webp"
                             alt="{{ $top_user->getTagAttribute() }}"/>
                        <p class="text-3xl text-purple-400 font-extrabold">{{ $top_user->displayName() }} <span class="text-gray-400 text-sm">({{ $top_user->id }})</span></p>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">{{ $top_hours }} | {{ $top_user->getHighestRole($server)->name }}</p>
                </div>
                <div class="bg-zinc-800 p-6 rounded-lg">
                    <p class="text-white font-extrabold text-xl">Timeclock Promotion Settings</p>
                    <p class="text-gray-400">Configure promotion hours per role.</p>
                    <livewire:role-timeclock :server="$server" />
                </div>
            </div>
            <div class="bg-purple-800 p-6 rounded-lg h-fit w-full lg:w-1/2">
                <div id="tooltip-leaderboard" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 rounded-lg shadow-sm opacity-0 tooltip bg-purple-600">
                    Leaderboard is updated every 15 minutes.
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <p class="text-white font-extrabold text-2xl flex items-center gap-2">Weekly Leaderboard
                    <svg data-tooltip-target="tooltip-leaderboard" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 stroke-zinc-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </p>
                <p class="text-gray-400">Who has the most amount of clocked hours</p>
                <div class="flex items-center mt-2" id="rank-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2 fill-yellow-300 stroke-yellow-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                    </svg>
                    <p class="text-sm text-gray-200 flex items-center">
                        @if(isset($server->getTopTimeclock()[0]))
                            <img class="h-8 w-8 border-2 rounded-full object-cover mr-2"
                                 src="https://cdn.discordapp.com/avatars/{{ $server->getTopTimeclock()[0]['user']->id }}/{{ $server->getTopTimeclock()[0]['user']->avatar }}.webp"
                                 alt="{{ $server->getTopTimeclock()[0]['user']->getTagAttribute() }}"/>
                            {{ $server->getTopTimeclock()[0]['user']->displayName() }} - {{ Carbon\CarbonInterval::minutes($server->getTopTimeclock()[0]['time'])->cascade()->format('%d Days, %h Hours, %i Minutes') }}
                        @else
                            <span class="text-gray-400">No one</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center mt-2" id="rank-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2 fill-gray-300 stroke-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                    </svg>
                    <p class="text-sm text-gray-200 flex items-center">
                        @if(isset($server->getTopTimeclock()[1]))
                            <img class="h-8 w-8 border-2 rounded-full object-cover mr-2"
                                 src="https://cdn.discordapp.com/avatars/{{ $server->getTopTimeclock()[1]['user']->id }}/{{ $server->getTopTimeclock()[1]['user']->avatar }}.webp"
                                 alt="{{ $server->getTopTimeclock()[1]['user']->getTagAttribute() }}"/>
                            {{ $server->getTopTimeclock()[1]['user']->displayName() }} - {{ Carbon\CarbonInterval::minutes($server->getTopTimeclock()[1]['time'])->cascade()->format('%d Days, %h Hours, %i Minutes') }}
                        @else
                            <span class="text-gray-400">No one</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center mt-2" id="rank-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2 fill-yellow-700 stroke-yellow-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                    </svg>
                    <p class="text-sm text-gray-200 flex items-center">
                        @if(isset($server->getTopTimeclock()[2]))
                            <img class="h-8 w-8 border-2 rounded-full object-cover mr-2"
                                 src="https://cdn.discordapp.com/avatars/{{ $server->getTopTimeclock()[2]['user']->id }}/{{ $server->getTopTimeclock()[2]['user']->avatar }}.webp"
                                 alt="{{ $server->getTopTimeclock()[2]['user']->getTagAttribute() }}"/>
                            {{ $server->getTopTimeclock()[2]['user']->displayName() }} - {{ Carbon\CarbonInterval::minutes($server->getTopTimeclock()[2]['time'])->cascade()->format('%d Days, %h Hours, %i Minutes') }}
                        @else
                            <span class="text-gray-400">No one</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center mt-2" id="rank-4">
                    <p class="text-sm text-gray-200 flex items-center">
                        <span class="mr-2">4. </span>
                        @if(isset($server->getTopTimeclock()[3]))
                            <img class="h-8 w-8 border-2 rounded-full object-cover mr-2"
                                 src="https://cdn.discordapp.com/avatars/{{ $server->getTopTimeclock()[3]['user']->id }}/{{ $server->getTopTimeclock()[3]['user']->avatar }}.webp"
                                 alt="{{ $server->getTopTimeclock()[3]['user']->getTagAttribute() }}"/>
                            {{ $server->getTopTimeclock()[3]['user']->displayName() }} - {{ Carbon\CarbonInterval::minutes($server->getTopTimeclock()[3]['time'])->cascade()->format('%d Days, %h Hours, %i Minutes') }}
                        @else
                            <span class="text-gray-400">No one</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center mt-2" id="rank-5">
                    <p class="text-sm text-gray-200 flex items-center">
                        <span class="mr-2">5. </span>
                        @if(isset($server->getTopTimeclock()[4]))
                            <img class="h-8 w-8 border-2 rounded-full object-cover mr-2"
                                 src="https://cdn.discordapp.com/avatars/{{ $server->getTopTimeclock()[4]['user']->id }}/{{ $server->getTopTimeclock()[4]['user']->avatar }}.webp"
                                 alt="{{ $server->getTopTimeclock()[4]['user']->getTagAttribute() }}"/>
                            {{ $server->getTopTimeclock()[4]['user']->displayName() }} - - {{ Carbon\CarbonInterval::minutes($server->getTopTimeclock()[4]['time'])->cascade()->format('%d Days, %h Hours, %i Minutes') }}
                        @else
                            <span class="text-gray-400">No one</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <livewire:timeclock-table :server="$server" />
        </div>
    </div>
</x-app-layout>

