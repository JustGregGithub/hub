<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300"><span class="font-normal text-gray-500 dark:text-gray-400">Application Settings</span> / {{ $category->name }}</h1>
            </div>
            <div class="bg-white dark:bg-slate-600 rounded-xl p-4 mt-4 dark:text-gray-300">
                <div class="bg-gray-50 dark:bg-slate-500 rounded-md p-4">
                    <div id="deleteModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Delete Application Category
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-2">
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Are you sure you want to delete the application category? This will also delete all active applications. <span class="font-extrabold text-red-500"> This is un-recoverable! </span></p>
                                    <form method="POST" action="{{ route('applications.settings.application.delete', $category->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="category_id" placeholder="Application Category" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $category->id }}" hidden>
                                        <input type="submit" value="Delete Application" class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="font-extrabold text-lg">Application Settings</span>
                    <div class="flex gap-4">
                        <livewire:input-box :model="$category" column="name" label="Name" />
                        <livewire:input-box :model="$category" column="description" label="Description" />
                    </div>
                    <livewire:select-dropdown column="application_section_id" label="Application Section" :model="$category" />
                    <hr class="h-px my-8 mt-4 mb-2 bg-gray-200 border-0 dark:bg-gray-700">

                    <div class="flex gap-4">
                        <form method="POST" action="{{ route('applications.settings.application.interview', $category->id) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-4">
                                <label for="interviewTicket" class="block text-sm font-medium text-gray-700 dark:text-gray-300 w-full">Enable Interview Tickets?
                                    <select name="interviewTicket" class="border border-gray-300 rounded-md text-sm mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="1" @if($category->create_interview == 1) selected @endif>Yes</option>
                                        <option value="0" @if($category->create_interview == 0) selected @endif>No</option>
                                    </select>
                                </label>

                                <label for="interviewCategory" class="block text-sm font-medium text-gray-700 dark:text-gray-300 w-full">Interview Ticket Category?
                                    <select name="category" class="border border-gray-300 rounded-md text-sm mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach($ticketCategories as $ticketCategory)
                                            <option value="{{ $ticketCategory->id  }}" @if($ticketCategory->id == $category->interview_category) selected @endif>{{ $ticketCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <input type="submit" value="Submit Interview Changes" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer mt-4">
                        </form>
                        <div>
                            <div class="flex flex-col justify-center">
                                <livewire:input-box :model="$category" column="guild" label="Guild ID" />
                                <div class="flex gap-4 items-baseline">
                                    @can('is-mgmt')
                                        <livewire:input-box :model="$category" column="manager_role" label="Manager Role ID" />
                                    @endcan
                                    <livewire:input-box :model="$category" column="worker_role" label="Worker Role ID" />
                                </div>
                            </div>
                            @can('is-mgmt')
                                <button class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer mt-4" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                    Delete Application Category
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-500 rounded-md mt-4 p-4">
                    <span class="font-extrabold">Application Information</span>
                    <form method="POST" action="{{ route('applications.settings.application.information', $category->id) }}" class="mt-2">
                        @csrf
                        @method('PATCH')
                        <textarea name="information" cols="30" rows="10">
                                {{ $category->information }}
                            </textarea>
                        <input type="submit" value="Update Category Information" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer mt-4">
                    </form>
                    <x-tiny-mce/>
                </div>
                <div class="bg-gray-50 dark:bg-slate-500 rounded-md mt-4 p-4">
                    <div class="flex justify-between text-lg">
                        <span class="font-extrabold">Application Questions</span>
                        <button href="" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-md" data-modal-target="createModal" data-modal-toggle="createModal">Create</button>
                    </div>

                    {{-- Create Modal --}}
                    <div id="createModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Create Question
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
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please enter the question and position you would like to add to the <span class="font-extrabold text-black">{{ $category->name }}</span> application</p>
                                    <form method="POST" action="{{ route('applications.settings.question.create', $category->id) }}">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="text" name="category_id" placeholder="Category" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $category->id }}" hidden>
                                            <input type="text" name="position" placeholder="Position" class="border border-gray-300 rounded-md text-sm">
                                            <input type="text" name="question" placeholder="Question" class="border border-gray-300 rounded-md text-sm w-full">
                                        </div>
                                        <input type="submit" value="Add question" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex flex-col">
                            @php
                                $sortedQuestions = $questions->sortBy('position');
                            @endphp

                            @foreach ($sortedQuestions as $question)
                                {{-- Move Modal --}}
                                <div id="moveModal-{{ $question->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Move Question
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="moveModal-{{ $question->id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please enter the new position this question.</p>
                                                <form method="POST" action="{{ route('applications.settings.question.move', $category->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="question_id" placeholder="Question" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $question->id }}" hidden>
                                                    <input type="text" name="position" placeholder="Position" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <input type="submit" value="Move Question" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="renameModal-{{ $question->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Rename Question
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="renameModal-{{ $question->id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please enter the new position for this question.</p>
                                                <form method="POST" action="{{ route('applications.settings.question.rename', $category->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="question_id" placeholder="Question" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $question->id }}" hidden>
                                                    <input type="text" name="question" placeholder="Question Name" class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <input type="submit" value="Rename Question" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="typeModal-{{ $question->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Change Question Type
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="renameModal-{{ $question->id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please select the type of the question and fill in any necessary information. If you'd like to change the pre-existing inputs (e.g existing select), you will need to input the same information in the respective order with the changes.</p>
                                                <form method="POST" action="{{ route('applications.settings.question.type', $category->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="question_id" placeholder="Question" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $question->id }}" hidden>
                                                    <select name="type" id="type-{{ $question->id }}" class="border border-gray-300 rounded-md text-sm mt-2 w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        @foreach(\App\Models\ApplicationQuestion::OPTION_TYPES as $key=>$value)
                                                            <option value="{{ $value }}" @if($question->type == $value) selected @endif>{{ $key }}</option>
                                                        @endforeach
                                                    </select>

                                                    <div id="options-{{ $question->id }}" @if(!in_array($question->type, [\App\Models\ApplicationQuestion::OPTION_TYPES['Select'], \App\Models\ApplicationQuestion::OPTION_TYPES['Radio']])) class="hidden" @else class="block" @endif >
                                                        <div id="inputContainer-{{ $question->id }}"></div>
                                                        <div id="buttons-{{ $question->id }}">
                                                            <button type="button" onclick="addInputField_{{$question->id}}()" class="bg-green-500 hover:bg-green-400 transition px-4 py-2 rounded-md">Add Input</button>
                                                            <button type="button" onclick="removeInputField_{{$question->id}}()" class="bg-red-500 hover:bg-red-400 transition px-4 py-2 rounded-md">Remove Input</button>

                                                        </div>
                                                    </div>

                                                    <input type="submit" value="Set Question Type" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="deleteModal-{{ $question->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Delete Question
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal-{{ $question->id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Are you sure you want to delete the question? <span class="font-extrabold text-red-500"> This is un-recoverable! </span></p>
                                                <form method="POST" action="{{ route('applications.settings.question.delete', $category->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="text" name="question_id" placeholder="Question" class="border border-gray-300 rounded-md text-sm hidden" value="{{ $question->id }}" hidden>
                                                    <input type="submit" value="Delete Question" class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="h-px my-8 mt-2 mb-2 bg-gray-200 border-0 dark:bg-gray-700 ">
                                <div class="flex flex-row justify-between items-center">
                                    <div class="flex flex-col">
                                        <span class="font-normal">{{ $question->position  }}. {{ $question->question }}</span>
                                        <span class="text-sm text-gray-500">{{ $question->description }}</span>
                                    </div>

                                    <button data-dropdown-toggle="dropdown-{{ $question->id }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Edit <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $question->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                            <li>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="moveModal-{{ $question->id }}" data-modal-toggle="moveModal-{{ $question->id }}">Move</a>
                                            </li>
                                            <li>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="renameModal-{{ $question->id }}" data-modal-toggle="renameModal-{{ $question->id }}">Rename</a>
                                            </li>
                                            <li>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="typeModal-{{ $question->id }}" data-modal-toggle="typeModal-{{ $question->id }}">Change Type</a>
                                            </li>
                                            <li>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-red-500" data-modal-target="deleteModal-{{ $question->id }}" data-modal-toggle="deleteModal-{{ $question->id }}">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        @foreach($category->questions as $question)
            document.getElementById('type-{{$question->id}}').addEventListener('change', function () {
                let value = this.value;
                value = parseInt(value);

                if (value === {{ \App\Models\ApplicationQuestion::OPTION_TYPES['Select'] }} || value === {{ \App\Models\ApplicationQuestion::OPTION_TYPES['Radio'] }}) {
                    document.getElementById('options-{{$question->id}}').classList.remove('hidden');
                    document.getElementById('buttons-{{$question->id}}').classList.add('mt-2');


                    console.log('show')
                } else {
                    document.getElementById('options-{{$question->id}}').classList.add('hidden');
                    document.getElementById('buttons-{{$question->id}}').classList.remove('mt-2');
                    console.log('hide')
                }
            })

            function addInputField_{{ $question->id }}() {
                const inputContainer = document.getElementById('inputContainer-{{$question->id}}');
                const inputCount = inputContainer.children.length + 1;

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'input[' + inputCount + ']'; // Use an index for numeric keys
                newInput.placeholder = 'Option ' + inputCount;
                newInput.className = 'border border-gray-300 rounded-md text-sm mt-2 w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';

                inputContainer.appendChild(newInput);
            }

            // Function to remove an input field
            function removeInputField_{{ $question->id }}() {
                const inputContainer = document.getElementById('inputContainer');
                if (inputContainer.children.length > 1) {
                    inputContainer.removeChild(inputContainer.lastChild);
                }
            }

            // Make 1 input field on page load
            addInputField_{{ $question->id }}();
        @endforeach
    </script>
</x-app-layout>
