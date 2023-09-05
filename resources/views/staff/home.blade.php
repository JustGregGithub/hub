<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><span class="font-normal">Hello</span>, {{ Auth::user()->displayName() }}!</h1>
        <span id="quote" class="text-gray-400 mt-2">View all servers that you have access to.</span>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-purple-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Server Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Players
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Highest Role
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\Staff\Server::all() as $server)
                    @can('can-view-server', $server)
                        <tr class="bg-zinc-800 text-gray-300 hover:bg-zinc-900 transition">
                            <td class="px-6 py-4">
                                {{ $server->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $server->isOnline() ? 'Online' : 'Offline' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $server->playerCount() }}
                            </td>
                            <td class="px-6 py-4">
                                {{ Auth::user()->getHighestRole($server)->name }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('staff.server', $server) }}" class="bg-purple-600 text-white px-4 py-2 rounded-md mt-5">View</a>
                            </td>
                        </tr>
                    @endcan
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

