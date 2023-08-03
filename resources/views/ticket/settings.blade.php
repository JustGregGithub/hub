<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 w-full">
                <div class="mt-10">
                    <h1 class="text-xl font-bold">Ticket Settings <span class="font-normal text-sm text-gray-500">- Modify & View Tickets / Categories</span>
                    </h1>
                </div>
                <div class="bg-white rounded-xl p-4 mt-4">
                    <span class="font-bold">Categories</span>
                    <div class="bg-gray-50 p-2 rounded-md mt-2 flex flex-wrap w-full">

{{--                        Left side --}}
                        <div class="flex flex-wrap w-full xl:w-11/12">
                            <form method="POST" action="{{ route('tickets.category.patch') }}" class="flex flex-wrap w-full">
                                @csrf
                                @method('PATCH')

                                <div class="w-full xl:w-4/12 p-2 flex items-center">
                                    <select name="category" id="category" class="border border-gray-300 rounded-md text-sm w-full">
                                        <option value="-1" disabled selected>-- Select a category</option>
                                        <option value="new">> Add Category</option>
                                        <option value="-1" disabled>&nbsp;</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id  }}">{{ $category->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full xl:w-1/12 hidden p-2 xl:flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                                        </svg>
                                </div>

                                <div class="w-full xl:w-6/12 p-2">
                                    <input type="text" placeholder="New Name" id="newname" name="newname" value="{{ old('newname') }}" class="w-full block border border-gray-300 rounded-md text-sm" maxlength="50" disabled="1">
                                    <input type="text" placeholder="Discord Role ID" id="role" name="role" value="{{ old('role') }}" class="w-full mt-2 block border border-gray-300 rounded-md text-sm" maxlength="20" disabled="1">
                                    <div class="flex items-center mt-2">
                                        <div id="tooltip-hidden-user" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-700 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Whether the category will be selectable when creating a ticket
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <input id="hidden" type="checkbox" name="isHidden" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" disabled="1">
                                        <label for="hidden" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" data-tooltip-target="tooltip-hidden-user">Is Hidden to User?</label>
                                    </div>
                                </div>

                                <div class="w-full xl:w-1/12 p-2 flex items-center">
                                    <input type="submit" value="Save" disabled="1"
                                           class="block w-full xl:inline-block xl:w-fit bg-purple-500 hover:bg-purple-400 rounded-md text-white px-4 py-2 transition cursor-pointer" id="save">
                                </div>
                            </form>
                        </div>

{{--                        Right side --}}
                        <div class="flex flex-wrap w-full xl:w-1/12 p-2 flex items-center">
                            <form method="POST" action="{{ route('tickets.category.delete') }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="category" value="-1" id="categoryClone"/>
                                <input type="submit" value="Delete" disabled="1"
                                       class="block w-full xl:inline-block xl:w-fit bg-red-500 hover:bg-red-400 rounded-md text-white px-4 py-2 transition cursor-pointer" id="delete">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 mt-4">
                    <span class="font-bold">Categories Information</span>

                    <div class="relative overflow-x-auto mt-2">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Category Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Linked Discord Role ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Is Hidden?
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    @if ($category->id != $defaultCategory->id)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $category->name }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $category->role }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($category->is_hidden == 1)
                                                    <span class="text-green-500">Yes</span>
                                                @else
                                                    <span class="text-red-500">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('category').addEventListener('change', function () {
            const defaultCategory = {{ $defaultCategory->id }};

            if (this.value == defaultCategory || this.value == "-1") {
                document.getElementById('delete').disabled = true;
                document.getElementById('save').disabled = true;
                document.getElementById('newname').disabled = true;
                document.getElementById('role').disabled = true;
                document.getElementById('hidden').disabled = true;
            } else {
                document.getElementById('delete').disabled = false;
                document.getElementById('save').disabled = false;
                document.getElementById('newname').disabled = false;
                document.getElementById('role').disabled = false;
                document.getElementById('hidden').disabled = false;
            }

            document.getElementById('categoryClone').value = this.value;
        })

        document.getElementById('hidden').addEventListener('change', function () {
            if (this.checked) {
                document.getElementById('hidden').value = 1;
            } else {
                document.getElementById('hidden').value = 0;
            }
        })
    </script>
</x-app-layout>
