<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 w-full">
                <div class="mt-10">
                    <div class="flex justify-between">
                        <h1 class="text-xl text-gray-400">Applications / <span class="font-bold text-black">{{ \App\Models\ApplicationCategory::name($application->application_category_id) }}</span></h1>
                        <div class="text-right">
                            <div class="bg-{{ \App\Models\Application::statusForeColor($application->status) }} rounded-md px-2 text-{{ \App\Models\Application::statusColor($application->status) }}">{{ \App\Models\Application::status($application->status) }}</div>

                            <small class="text-gray-400">{{ $application->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @if(Request::user()->id != $application->user_id)
                        <div class="mt-5 rounded-xl p-4 bg-white">
                            <p class="font-bold text-lg">Applicant Information</p>
                            <div class="flex items-center">
                                <img class="h-12 w-12 rounded-xl object-cover mr-2" src="https://cdn.discordapp.com/avatars/{{ $application->user_id }}/{{ $application->user->avatar }}.webp" alt="{{ $application->user->getTagAttribute() }}" />
                                <div>
                                    <p class="break-all">{{ $application->user->displayName()  }} @if ($application->user->discriminator != 0) #{{ $application->user->discriminator }} @endif</p>
                                    <p class="text-xs text-gray-400 break-all">{{ $application->user_id  }}</p>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-5">
                    @endif
                    @if($application->status == \App\Models\Application::STATUSES['Denied'])
                        <div class="mt-5 rounded-xl p-4 bg-white">
                            <p class="font-bold text-lg">Application Denied - Reason</p>
                            <p class="text-sm text-gray-400">
                                {{ $application->reason }}
                            </p>
                        </div>
                    @endif
                    <div class="mt-5 rounded-xl p-4 bg-white">
                        <p class="font-bold text-lg">Application Information</p>
                        {!! \App\Models\ApplicationCategory::information($application->id) !!}

                        <hr class="mt-5 mb-5">

                        <p class="font-bold text-lg">Questions</p>
                        <p class="text-sm text-gray-400">
                            Please answer the following questions to apply for {{ \App\Models\ApplicationCategory::name($application->application_category_id) }}. Answer the questions to the best of your ability and honestly. If any copying / cheating is found, the application will be instantly denied
                        </p>

                        @foreach($application->questions as $question)
                            <div>
                                <p class="font-bold mt-5">{{ $question['position'] }}. {{ $question['question'] }}</p>

                                <div class="mt-2">
                                    {{-- Each question has an answer. Search for the question id in the $application->content --}}
                                    {{ $application->content[$question['id']] }}
                                </div>
                            </div>
                        @endforeach

                        @if($application->status != \App\Models\Application::STATUSES['Denied'] && $application->status != \App\Models\Application::STATUSES['Accepted'] && Request::user()->id != $application->user_id)
                            @can('is-application-worker-of', $application->application_category_id)
                                <div id="acceptModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Accept Application
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="acceptModal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Are you sure you want to accept this application?
                                                </p>
                                                <form method="POST" action="{{ route('applications.status.patch', $application->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="flex gap-2">
                                                        <input class="hidden" type="hidden" name="status" value="{{ \App\Models\Application::STATUSES['Accepted'] }}">
                                                    </div>
                                                    <input type="submit" value="Accept Application" class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="denyModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Deny Application
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="denyModal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-2">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">Please give a reason for denying this application.
                                                </p>
                                                <form method="POST" action="{{ route('applications.status.patch', $application->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="flex gap-2">
                                                        <input class="hidden" type="hidden" name="status" value="{{ \App\Models\Application::STATUSES['Denied'] }}">
                                                        <textarea type="text" name="reason" class="border border-gray-300 rounded-md text-sm w-full">Not enough information</textarea>
                                                    </div>
                                                    <input type="submit" value="Deny Application" class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-5 mb-5">
                                <p class="text-sm text-gray-400 text-center">What do you think of this application?</p>
                                <div class="flex gap-4 justify-center mt-2">
                                    <button class="bg-green-300 text-green-500 px-2 py-1 rounded-md" data-modal-target="acceptModal" data-modal-toggle="acceptModal">
                                        Accept
                                    </button>
                                    <button class="bg-red-300 text-red-500 px-4 py-1 rounded-md" data-modal-target="denyModal" data-modal-toggle="denyModal">
                                        Deny
                                    </button>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
