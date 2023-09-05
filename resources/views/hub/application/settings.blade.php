<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300">Application Settings <span class="font-normal text-sm text-gray-500 dark:text-gray-400">- Modify & View Application settings</span>
                </h1>
            </div>
            @can('is-mgmt')
                <div id="createSectionModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Create Application Section
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="createSectionModal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-2">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please fill in the following information to create a new application section. Please also set the colours used for this section. Use hex codes for the colours. E.G From: <span style="color: #09ffcb;">09ffcb</span> To: <span style="color: #8eff00">8eff00</span>
                                </p>
                                <form method="POST" action="{{ route('applications.settings.section.create') }}">
                                    @csrf
                                    <input type="text" name="name" placeholder="Name" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <div class="flex gap-4 mt-2 justify-center">
                                        <input type="text" name="colour_left" class="border border-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" data-coloris>
                                        <input type="text" name="colour_right" class="border border-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" data-coloris>
                                    </div>
                                    <input type="submit" value="Add application" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-600 dark:text-gray-300 rounded-xl p-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold">Application Sections</span>
                        <button href="" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-md" data-modal-target="createSectionModal" data-modal-toggle="createSectionModal">Create</button>
                    </div>

                    @foreach($sections as $section)
                        <div id="renameModal-{{ $section->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Rename Application Section
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="renameModal-{{ $section->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-2">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please give this section a new name.
                                        </p>
                                        <form method="POST" action="{{ route('applications.settings.section.rename', $section->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="name" placeholder="Name" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <input type="submit" value="Add application" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="colourModal-{{ $section->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Set Application Section Colours
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="colourModal-{{ $section->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-2">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                            Set the colours used for this section.
                                        </p>
                                        <form method="POST" action="{{ route('applications.settings.section.colour', $section->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex gap-4 justify-center">
                                                <input type="text" name="colour_left" class="border border-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $section->colour_left }}" data-coloris>
                                                <input type="text" name="colour_right" class="border border-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $section->colour_right }}" data-coloris>
                                            </div>
                                            <input type="submit" value="Set Colours" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="defaultModal-{{ $section->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Set Default Application Section
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal-{{ $section->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-2">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Are you sure you want to set this section as the default section for new applications?</p>
                                        <form method="POST" action="{{ route('applications.settings.section.default', $section->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="submit" value="Set Default" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="deleteModal-{{ $section->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Delete Application Section
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal-{{ $section->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-2">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400 font-bold">Are you sure you want to delete this section? All applications in this section will be moved to the default section.</p>
                                        <form method="POST" action="{{ route('applications.settings.section.delete', $section->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Delete" class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="h-px my-8 mt-2 mb-2 bg-gray-200 border-0 dark:bg-gray-700">
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex items-center">
                                @if($section->is_default)
                                    <div class="rounded-full bg-orange-400 w-2 h-2 mr-2"></div>
                                @endif
                                <span class="font-normal">{{ $section->name }}</span>
                            </div>

                            <button data-dropdown-toggle="dropdown-{{ $section->id }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Edit <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <div id="dropdown-{{ $section->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="defaultModal-{{ $section->id }}" data-modal-toggle="defaultModal-{{ $section->id }}">Set as default</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="renameModal-{{ $section->id }}" data-modal-toggle="renameModal-{{ $section->id }}">Rename</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="colourModal-{{ $section->id }}" data-modal-toggle="colourModal-{{ $section->id }}">Set Colours</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-red-500" data-modal-target="deleteModal-{{ $section->id }}" data-modal-toggle="deleteModal-{{ $section->id }}">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center mt-4">
                        <div class="rounded-full bg-orange-400 w-2 h-2 mr-2"></div>
                        <span class="text-gray-400 text-sm">Default Section</span>
                    </div>
                </div>
            @endcan
            <div class="bg-white dark:bg-slate-600 rounded-xl p-4 mt-4">
                @can('is-mgmt')
                    <div id="createModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Create Application
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="createModal">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-2">
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please fill in the following information to create a new application.
                                    </p>
                                    <form method="POST" action="{{ route('applications.settings.application.create') }}">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="text" name="name" placeholder="Name" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <input type="text" name="description" placeholder="Description" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <input type="text" name="manager_role" placeholder="Manager Discord ID" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <input type="text" name="worker_role" placeholder="Worker Discord ID" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <input type="submit" value="Add application" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="font-extrabold dark:text-gray-300">Applications</span>
                        <button href="" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-md" data-modal-target="createModal" data-modal-toggle="createModal">Create</button>
                    </div>
                @else
                    <span class="font-extrabold dark:text-gray-300">Applications</span>
                @endcan

                @foreach($categories as $category)
                    <div class="bg-gray-50 dark:bg-slate-500 dark:text-gray-300 rounded-xl p-4 mt-4">
                        <div class="flex justify-between items-center">
                            <h2 class="truncate px-4 flex-shrink">
                                {{ $category->name }} <small class="text-gray-400">| {{ $category->description }}</small>
                            </h2>
                            <div class="flex gap-4 items-center">
                                <div id="tooltip-toggle" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-500 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Toggle whether the applications are open or closed.
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <livewire:toggle-component :model="$category" column="is_open" />
                                <a href="{{ route('applications.settings.edit', $category->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-auto text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
