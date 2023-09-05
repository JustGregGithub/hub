<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300">Ticket Support</h1>
                <div class="bg-white dark:bg-slate-600 rounded-xl p-4 mt-4 flex flex-col gap-4">
                    <span class="font-bold dark:text-gray-300">Your Available Categories</span>
                    @foreach($categories as $category)
                        @if($category != \App\Models\Hub\TicketCategory::getDefault())
                            <div class="bg-gray-100 dark:bg-slate-500 rounded-md p-4 w-full flex justify-between">
                                <p class="text-md dark:text-gray-300">
                                    {{ $category->name }}
                                </p>
                                <div class="flex gap-2">
                                    <div class="flex gap-2">
                                        @foreach(\App\Models\Hub\Ticket::STATUSES as $key=>$value)
                                            <div id="tooltip-{{ $key }}" role="tooltip"
                                                 class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-{{ \App\Models\Hub\Ticket::statusColor($value) }} rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                {{ $key }} Tickets
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                            <p class="text-{{ \App\Models\Hub\Ticket::statusColor($value) }}"
                                               data-tooltip-target="tooltip-{{ $key }}">{{ $category->statusCount($value) }}</p>
                                            <p class="text-gray-400">|</p>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('tickets.support_tickets', $category->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5"/>
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
</x-app-layout>
