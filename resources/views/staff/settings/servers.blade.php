<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold">Server Settings</h1>
        <span id="quote" class="text-gray-400 mt-2">Manage servers used by this panel.</span>

        <button class="flex justify-end w-full" data-modal-toggle="createModal">
            <div href="" class="bg-purple-600 text-white px-4 py-2 rounded-md mt-5 flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z" />
                </svg>

                Add Server
            </div>
        </button>
        <div id="createModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-zinc-800 rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b border-zinc-700 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Add Server
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-zinc-700 hover:text-white rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="createModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-2">
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please fill in the following information to create a new server.</p>
                        <form method="POST" action="{{ route('staff.server.post') }}">
                            @csrf
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                            <div class="flex gap-4 mt-2">
                                <input type="text" name="ip" value="{{ old('ip') }}" placeholder="192.168.1.1" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                <input type="text" name="port" value="{{ old('port') }}" placeholder="30120" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                            </div>
                            <input type="submit" value="Add Server" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-4 cursor-pointer">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- if secret is in session --}}
        @if(Session::has('secret'))
            <div id="alert" class="flex items-center p-4 mb-4 rounded-lg bg-zinc-800 text-red-400 mt-5" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div class="ml-3 text-sm font-medium">
                    Your one-time API key is <kbd class="font-bold bg-zinc-900 p-1 rounded-md">{{ Session::get('secret') }}</kbd>. Please copy this key as it will not be shown again.
                </div>
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-purple-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Server Identifier
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Server Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Server IP
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Server Port
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($servers as $server)
                    <tr class="bg-zinc-800 text-gray-300 hover:bg-zinc-900 transition">
                        <td class="px-6 py-4">
                            {{ $server->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $server->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $server->ip }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $server->port }}
                        </td>
                        <td class="px-6 py-4 flex gap-4">
                            <a href="{{ route('staff.settings.server', $server->id) }}" class="p-2 rounded-md bg-purple-400 text-purple-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <button class="p-2 rounded-md bg-purple-400 text-red-700" data-modal-toggle="deleteModal-{{ $server->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

