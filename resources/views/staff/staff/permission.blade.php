<x-app-layout>
    <div class="p-12">
        <h1 class="text-3xl text-white font-extrabold"><a href="" class="text-gray-300">Manage Permissions</a> / {{ $role->name }}</h1>
        <span id="quote" class="text-gray-400 mt-2">Set role permissions for {{ $role->name }} in {{ $server->name }}.</span>
        <div class="grid grid-cols-1 md:grid-cols-2 mt-5 gap-4">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-purple-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Permission Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Is Allowed?
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- for each \app\Models\Staff\ServerRole::PERMISSIONS, get the column with that name in the $role variable --}}
                    @foreach(\App\Models\Staff\ServerRole::PERMISSIONS as $permission)
                        <tr class="bg-zinc-800 text-gray-300 hover:bg-zinc-900 transition">
                            <td class="px-6 py-4">
                                {{ Str::title(str_replace('_', ' ', $permission)) }}
                            </td>
                            <td class="px-6 py-4">
                                <livewire:toggle-component :model="$role" :column="$permission"/>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-zinc-800 rounded-md h-fit">
                <div class="text-xs font-bold text-gray-700 uppercase bg-purple-400 px-6 py-3 rounded-tl-md rounded-tr-md">
                    <p>Permission Details</p>
                </div>
                <form method="POST" action="{{ route('staff.permission.patch', ['server' => $server->id, 'role' => $role->id]) }}" class="p-6">
                    @csrf
                    @method('PATCH')
                    <div class="flex w-full gap-4">
                        <div class="w-full">
                            <label class="text-gray-400" for="name">Role Name</label>
                            <input type="text" name="name" placeholder="Role Name" value="{{ $role->name }}"
                                   class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="text-gray-400" for="priority">Role Priority</label>
                            <input type="number" name="priority" placeholder="Role Priority"
                                   value="{{ $role->priority }}"
                                   class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div class="w-full mt-4">
                        <label class="text-gray-400" for="guild">Guild ID</label>
                        <input type="text" name="guild" placeholder="Role Name" value="{{ $role->guild }}"
                               class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                    </div>
                    <div class="mt-4">
                        <label class="text-gray-400" for="role">Role ID</label>
                        <input type="number" name="role" placeholder="Role Priority" value="{{ $role->id }}"
                               class="border rounded-md text-sm w-full bg-zinc-900 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-purple-500">
                    </div>
                    <input type="submit" value="Update Details"
                           class="bg-purple-500 hover:bg-purple-600 text-white rounded-md px-4 py-2 mt-4 w-full cursor-pointer transition">
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

