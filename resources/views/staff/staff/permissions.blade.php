<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><span class="text-gray-300">Manage Permissions</span> / {{ $server->name }}</h1>
        <span id="quote" class="text-gray-400 mt-2">Set role permissions in {{ $server->name }}</span>

        @can('is-mgmt')
            <div class="flex justify-end w-full">
                <button class="bg-purple-600 text-white px-4 py-2 rounded-md mt-5 flex gap-2" data-modal-toggle="createModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    Add Role
                </button>
            </div>
            <div id="createModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-zinc-800 rounded-lg shadow">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b border-zinc-700 rounded-t">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Add Role
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
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please fill in the following information to create a new role.</p>
                            <form method="POST" action="{{ route('staff.permissions.post', $server->id) }}">
                                @csrf
                                <input type="text" name="name" placeholder="Name" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                <div class="flex gap-4 mt-2">
                                    <input type="text" name="guild" placeholder="Guild ID" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                    <input type="text" name="role" placeholder="Role ID" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                </div>
                                <input type="submit" value="Add Role" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-4 cursor-pointer">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endcan

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-purple-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Role Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Guild ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Allowed Permissions
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Denied Permissions
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Is Locked
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles->sortBy('priority') as $role)
                    @php
                        $availableCount = 0;
                        $deniedCount = 0;
                    @endphp
                    @foreach($role->getAttributes() as $attribute => $value)
                        @if (Str::startsWith($attribute, 'can_'))
                            @if ($value == 1)
                                @php
                                    $availableCount++;
                                @endphp
                            @elseif ($value == 0)
                                @php
                                    $deniedCount++;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <div id="deleteModal-{{ $role->id }}" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-zinc-800 rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b border-zinc-700 rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Delete Role
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-zinc-700 hover:text-white rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="deleteModal-{{ $role->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-2">
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please select the new role for all current members.</p>
                                    <form method="POST" action="{{ route('staff.permissions.delete', $server->id) }}">
                                        @csrf
                                        <input hidden type="text" name="id" placeholder="Id" class="hidden border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                        <select name="id" class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                                            @foreach($roles as $roleList)
                                                @if($roleList->id != $role->id)
                                                    <option value="{{ $roleList->id }}" selected>{{ $roleList->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="submit" value="Delete & Set New Role" class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-4 cursor-pointer">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <tr class="bg-zinc-800 text-gray-300 hover:bg-zinc-900 transition">
                        <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                            {{ $role->name }}
                        </td>
                        <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                            {{ $role->guild }}
                        </td>
                        <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                            {{ $role->id }}
                        </td>
                        <td class="px-6 py-4 text-green-400">{{ $availableCount }}</td>
                        <td class="px-6 py-4 text-red-400">{{ $deniedCount }}</td>
                        <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                            @if($role->is_locked)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex gap-4">
                            @if(Auth::user()->getHighestRole($server)->priority > $role->priority)
                                <a href="{{ route('staff.permission', ['server' => $server, 'role' => $role->id]) }}"
                                   class="p-2 rounded-md bg-purple-400 text-purple-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                            @endif
                            @if($roles->count() > 1)
                                @if(Auth::user()->getHighestRole($server)->priority > $role->priority)
                                    <button class="p-2 rounded-md bg-purple-400 text-red-700"
                                            data-modal-toggle="deleteModal-{{ $role->id }}"
                                            @if(Auth::user()->getHighestRole($server)->priority < $role->priority)
                                                disabled
                                        @endif>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                @endif
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

