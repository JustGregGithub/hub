<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 w-full">
                <div class="mt-10">
                    <h1 class="text-xl text-gray-400">Applications / <span class="font-bold text-black">{{ $application->name }}</span></h1>

                    <div class="mt-5 rounded-xl p-4 bg-white">
                        <p class="font-bold text-lg">Application Information</p>
                        {!! $application->information !!}

                        <hr class="mt-5">

                        <p class="font-bold text-lg mt-4">Questions</p>
                        <p class="text-sm text-gray-400">
                            Please answer the following questions to apply for {{ $application->name }}. Answer the questions to the best of your ability and honestly. If any copying / cheating is found, the application will be instantly denied
                        </p>
                        <form method="POST" action="{{ route('applications.apply.post', $application->id) }}" class="mt-5">
                            @csrf
                            @foreach ($application->questions as $question)
                                <div class="mt-2">
                                    <p>
                                        {{ $question->position }}. {{ $question->question }}
                                    </p>
                                    <input
                                        type="text"
                                        class="border border-gray-300 rounded-md text-sm w-full"
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
    </div>
</x-app-layout>
