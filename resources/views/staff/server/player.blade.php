<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><a href="{{ route('staff.server.players', $server->id) }}" class="text-gray-300">{{ $server->name }} / Players</a> / {{ $player->name }}</h1>
        <span id="quote" class="text-gray-400 mt-2">{{ $player->name }}'s profile. Apply punishments, check records, and more.</span>
        @if($player->isBanned())
            <div class="rounded-md p-4 mb-4 mt-4 border-red-800 bg-red-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 text-white">
                            This player is currently banned!
                        </h3>
                    </div>
                </div>
            </div>
        @endif
        <div class="bg-zinc-900 rounded-lg shadow-xl pb-8 mt-5">
            <div class="w-full h-[250px]">
                <div class="w-full h-full rounded-tl-lg rounded-tr-lg bg-gradient-to-r from-rose-400 via-fuchsia-500 to-indigo-500"> </div>
            </div>
            <div class="flex flex-col items-center -mt-20 z-10">
                <img src="{{ $steam['avatarfull'] }}" class="w-40 border-4 border-white rounded-full" alt="{{ $player->name }}'s Avatar Picture">
                <p class="text-2xl text-white font-bold mt-2">{{ $player->name }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
            <div class="flex flex-col xl:flex-row gap-4">
                <div class="bg-zinc-800 rounded-md h-fit xl:w-3/5 h-full">
                    <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                        <p>Player Identifiers</p>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">Steam</p>
                            <a href="{{ $steam['profileurl'] }}" class="text-md text-purple-400 font-extrabold blur hover:blur-none transition break-words">{{ $player->steam }}</a>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">Discord</p>
                            <p class="text-md text-purple-400 font-extrabold blur hover:blur-none transition break-words">
                                {{ $player->discord }}
                            </p>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">License</p>
                            <p class="text-md text-purple-400 font-extrabold blur hover:blur-none transition break-words">
                                {{ $player->license }}
                            </p>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">IP Address</p>
                            <p class="text-md text-purple-400 font-extrabold blur hover:blur-none transition break-words">
                                @can('is-mgmt')
                                    {{ $player->ip }}
                                @else
                                    Hidden
                                @endcan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-zinc-800 rounded-md h-fit xl:flex-grow h-full">
                    @php
                        $interval = Carbon\CarbonInterval::minutes($player->playtime);

                        $formattedInterval = $interval->cascade()->forHumans([
                            'join' => ', ',
                            'parts' => 3,
                        ]);
                    @endphp
                    <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                        <p>Player Information</p>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">Status</p>
                            <p class="text-md text-purple-400 font-extrabold">
                                @if($player->online)
                                    <p class="text-md text-green-400 font-extrabold">
                                        Online
                                    </p>
                                @else
                                    <p class="text-md text-red-400 font-extrabold">
                                        Offline
                                    </p>
                                @endif
                            </p>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">Playtime</p>
                            <p class="text-md text-purple-400 font-extrabold">
                                {{ $formattedInterval }}
                            </p>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">First Joined</p>
                            <p class="text-md text-purple-400 font-extrabold">
                                {{ $player->created_at->diffForHumans() }} ({{ $player->created_at->format('d/m/Y') }})
                            </p>
                        </div>
                        <div class="bg-zinc-900 p-6 rounded-lg">
                            <p class="text-white font-extrabold text-xl">Trustscore</p>
                            <p class="text-md text-purple-400 font-extrabold">
                                {{ $player->trust_score }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-zinc-800 rounded-md">
                    <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                        <p>Punish Player</p>
                    </div>
                    <div x-data="{ tab: '{{ Arr::first(\App\Models\Staff\PlayerRecord::TYPES) }}'}">
                        <div class="flex mb-2 space-x-4 text-xl border-b border-zinc-700 px-4">
                            @foreach(\App\Models\Staff\PlayerRecord::TYPES as $name => $type)
                                <div class="hover:text-purple-400 p-2 cursor-pointer pl-2 text-base" :class="{'text-purple-400': tab == '{{ $type }}', 'text-zinc-500': tab != '{{ $type }}'}" @click="tab = '{{ $type }}'">{{ $name }}</div>
                            @endforeach
                        </div>

                        <div class="p-2">
                            @foreach(\App\Models\Staff\PlayerRecord::TYPES as $name => $type)
                                @if($type != \App\Models\Staff\PlayerRecord::TYPES['Ban'])
                                    <form x-show="tab == '{{ $type }}'" method="POST" action="{{ route('staff.server.player.post', ['server' => $server->id, 'player' => $player->license]) }}">
                                        @csrf
                                        <input type="number" name="type" value="{{ $type }}" hidden class="hidden">
                                        <textarea type="text" name="message" placeholder="Message" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500" rows="5">{{ old($type) }}</textarea>
                                        <input type="submit" value="Submit {{ $name }}" class="bg-purple-500 hover:bg-purple-600 text-white rounded-md px-4 py-2 mt-2 w-full cursor-pointer transition">
                                    </form>
                                @else
                                    <form x-show="tab == '{{ $type }}'" method="POST" action="{{ route('staff.server.player.post', ['server' => $server->id, 'player' => $player->license]) }}">
                                        @csrf
                                        <input type="number" name="type" value="{{ $type }}" hidden class="hidden">
                                        <textarea type="text" name="message" placeholder="Message" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500" rows="5">{{ old($type) }}</textarea>
                                        <div class="flex flex-col lg:flex-row gap-2">
                                            <input type="number" name="duration" placeholder="Duration" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                            <select name="duration_type" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                                <option value="second">Second(s)</option>
                                                <option value="minute">Minute(s)</option>
                                                <option value="hour">Hour(s)</option>
                                                <option value="day">Day(s)</option>
                                                <option value="week">Week(s)</option>
                                                <option value="year">Year(s)</option>
                                            </select>
                                        </div>
                                        <input type="submit" value="Submit {{ $name }}" class="bg-purple-500 hover:bg-purple-600 text-white rounded-md px-4 py-2 mt-2 w-full cursor-pointer transition">
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="bg-zinc-800 rounded-md mt-4">
                    <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                        <p>Recent Activity</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-none">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-zinc-700 text-gray-300">
                                <span>Type</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-zinc-700 text-gray-300">
                                <span>Date</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-zinc-700 text-gray-300">
                                <span>Message</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 divide-none rounded-md">
                        @if($player->activity->count() == 0)
                            <tr class="bg-zinc-800 dark:text-white" wire:key="row-0-zswiXGCkMWdqutaheIcE">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white" wire:key="cell-0-0-zswiXGCkMWdqutaheIcE">
                                    <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">No Activity</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white" wire:key="cell-0-0-zswiXGCkMWdqutaheIcE">
                                    <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">No Activity</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white" wire:key="cell-0-1-zswiXGCkMWdqutaheIcE">
                                    <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">No Activity</span>
                                </td>
                            </tr>
                        @endif

                        @foreach($player->activity->reverse()->take(3) as $activity)
                            <tr class="bg-zinc-800 dark:text-white" wire:key="row-0-zswiXGCkMWdqutaheIcE">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white" wire:key="cell-0-0-zswiXGCkMWdqutaheIcE">
                                    @if($activity->type == \App\Models\Staff\PlayerActivity::TYPES['Join'])
                                        <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-green-900 text-green-300">Join</span>
                                    @elseif($activity->type == \App\Models\Staff\PlayerActivity::TYPES['Leave'])
                                        <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">Leave</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-normal truncate text-sm font-medium dark:text-white" aria-label="test" wire:key="cell-0-1-zswiXGCkMWdqutaheIcE">
                                    <label title="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</label>
                                </td>

                                <td class="px-6 py-4 whitespace-normal truncate text-sm font-medium dark:text-white" wire:key="cell-0-1-zswiXGCkMWdqutaheIcE">
                                    {{ $activity->message }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="bg-zinc-800 rounded-md h-fit mt-4 p-6">
            <livewire:records-table :server="$server" :player="$player" />
        </div>
    </div>
</x-app-layout>

