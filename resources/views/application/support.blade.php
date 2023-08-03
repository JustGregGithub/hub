<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 w-full">
                <div class="mt-10">
                    <h1 class="text-xl font-bold">Application Support</h1>
                    <div class="bg-white rounded-xl p-4 mt-4 flex flex-col gap-4">
                        <span class="font-bold">Your Available Categories</span>
                        <span class="text-sm text-gray-400">See all of the available application categories and view some quick statistics about each category.</span>
                        @foreach($categories as $category)
                            @if($category != \App\Models\TicketCategory::getDefault())
                                <div class="bg-gray-100 rounded-md p-4 w-full flex justify-between">
                                    <p class="text-md">
                                        {{ $category->name }}
                                    </p>
                                    <div class="flex gap-2">
                                        <div class="flex gap-2">
                                            @foreach(\App\Models\Application::STATUSES as $key=>$value)
                                                <div id="tooltip-{{ $key }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-{{ \App\Models\Application::statusColor($value) }} rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    {{ $key }} Applications
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                                <p class="text-{{ \App\Models\Application::statusColor($value) }}" data-tooltip-target="tooltip-{{ $key }}">{{ $category->statusCount($value) }}</p>
                                                <p class="text-gray-400">|</p>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('application.support_applications', $category->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
