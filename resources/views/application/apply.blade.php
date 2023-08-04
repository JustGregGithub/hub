<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl text-gray-400">Applications / <span class="font-bold text-black dark:text-gray-300">{{ $application->name }}</span></h1>

                <div class="mt-5 rounded-xl p-4 bg-white dark:bg-slate-600">
                    <p class="font-bold text-lg dark:text-gray-300">Application Information</p>
                    <div class="dark:text-gray-400">
                        {!! $application->information !!}
                    </div>

                    <hr class="h-px my-8 mt-5 mb-5 bg-gray-200 border-0 dark:bg-gray-700">

                    <p class="font-bold text-lg dark:text-gray-300 mt-4">Questions</p>
                    <p class="text-sm text-gray-400 dark:text-gray-400">
                        Please answer the following questions to apply for {{ \App\Models\ApplicationCategory::name($application->application_category_id) }}. Answer the questions to the best of your ability and honestly. If any copying / cheating is found, the application will be instantly denied
                    </p>
                    <form method="POST" action="{{ route('applications.apply.post', $application->id) }}" class="mt-5">
                        @csrf
                        @foreach ($application->questions as $question)
                            <div class="mt-2">
                                <p class="dark:text-gray-300">
                                    {{ $question->position }}. {{ $question->question }}
                                </p>
                                <input
                                    type="text"
                                    class="border border-gray-300 rounded-md text-sm w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    name="questions[{{ $question->id }}]"
                                    value="{{ old('questions.'.$question->id) }}"
                                >
                            </div>
                        @endforeach
                        <input type="submit" value="Submit Application" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                    </form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
