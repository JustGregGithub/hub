<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <div class="flex justify-between">
                    <h1 class="text-xl font-bold dark:text-gray-400">Create a ticket</h1>
                </div>
                <div class="bg-white dark:bg-slate-600 rounded-xl p-4 mt-4">
                    <form method="POST" action="{{ route('tickets.create.post')  }}">
                        @csrf
                        <div class="flex gap-4 w-full">
                            <div class="w-full">
                                <label for="ticketTitle"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Ticket
                                    Title</label>
                                <input type="text" id="ticketTitle" name="title" value="{{ old('title') }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="This is my ticket title">
                            </div>
                            <div class="w-full">
                                <label for="ticketCategory"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Ticket
                                    Category</label>
                                <select id="ticketCategory" name="category"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @foreach($categories as $category)
                                        @if($category->id != \App\Models\Hub\TicketCategory::getDefault()->id && $category->is_hidden != 1)
                                            <option value="{{ $category->id  }}"
                                            @if($category->id == old('category')) selected @endif>{{ $category->name  }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                                <textarea name="content">
                                    {{ old('content') }}
                                </textarea>
                        </div>
                        <button type="submit"
                                class="mt-3 p-2 bg-purple-300 hover:bg-purple-400 text-purple-500 hover:text-white transition rounded-md cursor-pointer">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-tiny-mce/>
</x-app-layout>
