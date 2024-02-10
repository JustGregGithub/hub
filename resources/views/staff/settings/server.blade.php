<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><a href="{{ route('staff.settings.servers') }}" class="text-gray-300">Server Settings</a> / {{ $server->name }}</h1>
        <span id="quote" class="text-gray-400 mt-2">Manage servers used by this panel.</span>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
            <div class="space-y-4">
                <div class="bg-zinc-800 rounded-md h-fit">
                    <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                        <p>Server Details</p>
                    </div>
                    <form method="POST" action="{{ route('staff.settings.server.patch', $server->id) }}" class="p-6">
                        @csrf
                        @method('PATCH')
                        <label class="text-gray-400" for="name">Server Name</label>
                        <input type="text" name="name" placeholder="Server Name" value="{{ $server->name }}" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                        <div class="flex w-full gap-4 mt-4">
                            <div class="w-full">
                                <label class="text-gray-400" for="guild">Server IP</label>
                                <input type="text" name="ip" placeholder="Server IP" value="{{ $server->ip }}" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="text-gray-400" for="priority">Server Port</label>
                                <input type="number" name="port" placeholder="Server Port" value="{{ $server->port }}" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="text-gray-400" for="fetching_rate">Fetching Rate</label>
                                <input type="text" name="fetching_rate" placeholder="Fetching Rate" value="{{ $server->fetching_rate }}" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                            </div>
                        </div>
                        <input type="submit" value="Update Details" class="bg-purple-500 hover:bg-purple-600 text-white rounded-md px-4 py-2 mt-4 w-full cursor-pointer transition">
                    </form>
                </div>
            </div>
            <div class="bg-zinc-800 rounded-md h-fit">
                <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                    <p>Server Statistics</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Server Status</p>
                        <p class="text-gray-400">The amount of punishments today.</p>
                        @if($server->isOnline())
                            <p class="text-3xl text-green-400 font-extrabold mt-5">Online</p>
                        @else
                            <p class="text-3xl text-red-400 font-extrabold mt-5">Offline</p>
                        @endif
                    </div>
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Player Count</p>
                        <p class="text-gray-400">The amount of punishments today.</p>
                        @if($server->playerCount())
                            <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->playerCount() }}</p>
                        @else
                            <p class="text-3xl text-red-400 font-extrabold mt-5">Unavailable</p>
                        @endif
                    </div>
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Total Players</p>
                        <p class="text-gray-400">The amount of players ever.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->players->count() }}</p>
                    </div>
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Total Punishments</p>
                        <p class="text-gray-400">The amount of punishments ever.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->records->count() }}</p>
                    </div>
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Total Chats</p>
                        <p class="text-gray-400">The amount of chats ever.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->chats->count() }}</p>
                    </div>
                    <div class="bg-zinc-900 p-6 rounded-lg">
                        <p class="text-white font-extrabold text-xl">Total Deaths</p>
                        <p class="text-gray-400">The amount of deaths.</p>
                        <p class="text-3xl text-purple-400 font-extrabold mt-5">{{ $server->deaths->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

