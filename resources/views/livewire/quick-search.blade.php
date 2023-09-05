<div>
    <input wire:model.debounce.500ms="player_id" type="number" placeholder="Player ID" class="block w-full shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5  focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-zinc-800 text-white border-zinc-700 hover:bg-zinc-600 rounded-md ">

    @if($server->isOnline())
        @if(!$userStartedTyping)
            <div class="rounded-md p-4 mb-4 mt-4 border-purple-800 bg-purple-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-purple-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 text-white">
                            Please enter a valid player ID. For example, <kbd>1</kbd>.
                        </h3>
                    </div>
                </div>
            </div>
        @elseif(!$response)
            <div class="rounded-md p-4 mb-4 mt-4 border-red-800 bg-red-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 text-white">
                            This player is not online or the server is not responding.
                        </h3>
                    </div>
                </div>
            </div>
        @else
            <table class="min-w-full divide-y divide-none mt-4">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700" wire:key="header-col-2-9ICWJxS7n4b3NZloXCSJ">
                        Player ID
                    </th>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700" wire:key="header-col-1-9ICWJxS7n4b3NZloXCSJ">
                        Name
                    </th>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700" wire:key="header-col-1-9ICWJxS7n4b3NZloXCSJ">
                        License
                    </th>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700" wire:key="header-col-2-9ICWJxS7n4b3NZloXCSJ">
                        Discord
                    </th>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700" wire:key="header-col-4-9ICWJxS7n4b3NZloXCSJ">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-gray-800 divide-none">
                <tr class="bg-zinc-800 dark:text-white">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                        {{ $player_id }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                        {{ $response->name }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white blur hover:blur-none transition">
                        {{ $response->license }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white blur hover:blur-none transition">
                        {{ $response->discord }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                        <div class="space-x-2">
                            <a href="{{ route('staff.server.player', ['server' => $server, 'player' => $response->license]) }}" target="_blank" class="px-4 py-2 bg-purple-500 rounded-md">View</a>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
        @endif
    @else
        <div class="rounded-md p-4 mb-4 mt-4 border-red-800 bg-red-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 text-white">
                        The server is offline. Please try again later.
                    </h3>
                </div>
            </div>
        </div>
    @endif
</div>
