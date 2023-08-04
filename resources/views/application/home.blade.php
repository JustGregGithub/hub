<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300">My Applications</h1>
                @foreach($applications as $application)
                    <div class="bg-white dark:bg-slate-600 rounded-xl p-2 mt-4">
                        <div class="p-2 flex justify-between">
                            <div class="flex">
                                <div class="bg-{{ \App\Models\Application::statusForeColor($application->status)  }} rounded-md px-2 text-{{ \App\Models\Application::statusColor($application->status)  }}">{{ \App\Models\Application::status($application->status) }}</div>
                                <h2 class="ml-5 dark:text-gray-300">{{ \App\Models\ApplicationCategory::name($application->application_category_id) }} <small class="text-gray-400">| {{ $application->updated_at->diffForHumans() }}</small></h2>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('applications.view', $application->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-auto text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($applications->isEmpty())
                    <div class="bg-white dark:bg-slate-600 dark:text-gray-300 rounded-xl p-2 mt-4">
                        <div class="p-2">
                            No Applications Submitted
                        </div>
                    </div>
                @endif
            </div>
            <hr class="h-px my-8 mt-5 mb-5 bg-gray-200 border-0 dark:bg-gray-700">
            <div class="mt-5">
                <h1 class="text-xl font-bold dark:text-gray-300">Available Applications</h1>
                @foreach ($application_sections as $application_section)
                    @if($application_section->categories->where('is_open', true)->isNotEmpty())
                        <div class="mt-5 rounded-xl p-4" style="background: linear-gradient(138deg, {{ $application_section->colour_left }} 27%, {{ $application_section->colour_right }} 100%);">
                            <p class="font-bold text-lg">{{ $application_section->name }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                                @foreach ($application_section->categories as $category)
                                    @if($category->is_open)
                                        <div class="border rounded-xl p-4 text-center bg-gray-50 h-fit">
                                            <p>
                                                {{ $category->name }}
                                            </p>
                                            <p class="text-sm text-gray-400">
                                                {{ $category->description }}
                                            </p>
                                            <div class="mt-6">
                                                <a href="@if(\App\Models\Application::where('application_category_id', $category->id)->where('user_id', Request::user()->id)->where('status', \App\Models\Application::STATUSES['Under Review'])->exists()) #" @else {{ route('applications.apply', $category->id) }}" @endif class="bg-purple-200 px-4 py-2 rounded-md text-purple-600 @if(\App\Models\Application::where('application_category_id', $category->id)->where('user_id', Request::user()->id)->where('status', \App\Models\Application::STATUSES['Under Review'])->exists()) cursor-not-allowed @endif">
                                                Apply Now
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
