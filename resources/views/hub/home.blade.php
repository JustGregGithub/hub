<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10 dark:text-gray-400">
                <h1 class="text-xl font-bold">Hey, <span
                            class="bg-gradient-to-r from-fuchsia-600 to-pink-600 bg-clip-text text-transparent">{{ Auth::user()->getTagAttribute() }}</span>
                    👋</h1>
                <p class="mt-1">Let&apos;s see what&apos;s in-store today.</p>
            </div>
            <div class="bg-white dark:bg-slate-600 rounded-xl p-4">
                <h1 class="text-xl font-bold dark:text-gray-400">Quick Actions</h1>
                <div class="flex flex-wrap gap-4 mt-4">
                    <a href="{{ route('tickets.create') }}" class="bg-purple-200 px-4 py-2 rounded-xl text-purple-600">
                        🎫 Create a Ticket
                    </a>
                    <a href="{{ route('tickets.home') }}" class="bg-purple-200 px-4 py-2 rounded-xl text-purple-600">
                        🎫 View My Tickets
                    </a>
                    <a href="/dashboard" class="bg-purple-200 px-4 py-2 rounded-xl text-purple-600">
                        🚗 My Vehicles
                    </a>
                    <a href="{{ route('store') }}" class="bg-purple-200 px-4 py-2 rounded-xl text-purple-600">
                        👜 Store
                    </a>
                </div>
            </div>
            <div class="mt-10">
                <h1 class="text-xl dark:text-gray-400 font-bold">My Pinned Tickets</h1>
                @foreach($pinned_tickets as $ticket)
                    <div class="bg-white dark:bg-slate-600 rounded-xl p-2 mt-4">
                        <div class="p-2 flex justify-between items-center">
                            <div class="flex">
                                <div class="bg-gray-200 rounded-md px-2">#{{ $ticket->id }}</div>
                                <h2 class="ml-5 dark:text-gray-300">
                                    {{ $ticket->title }}
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">({{\App\Models\Hub\Ticket::status($ticket->status)}}) | Last Updated: {{$ticket->updated_at->diffForHumans()}}</span>
                                </h2>
                            </div>
                            <div class="flex gap-4 items-center">
                                <a href="{{ route('tickets.view', $ticket->slug) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="h-6 w-auto text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('tickets.pin.patch', $ticket->slug) }}"
                                      class="flex">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor"
                                             class="w-auto h-6 text-red-500 hover:text-gray-400 transition fill-current">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($pinned_tickets->isEmpty())
                    <div class="bg-white dark:bg-slate-600 rounded-xl p-2 mt-4">
                        <div class="p-2 dark:text-gray-300">
                            No Pinned Tickets
                        </div>
                    </div>
                @endif
            </div>
            <div class="mt-10">
                <h1 class="text-xl dark:text-gray-400 font-bold">My Recent Applications <span
                            class="font-normal text-sm text-gray-500">- Showing {{ count($applications) }} Application(s)</span>
                </h1>
                @foreach($applications as $application)
                    <div class="bg-white dark:bg-slate-600 rounded-xl p-2 mt-4">
                        <div class="p-2 flex justify-between">
                            <div class="flex">
                                <div class="bg-{{ \App\Models\Hub\Application::statusForeColor($application->status)  }} rounded-md px-2 text-{{ \App\Models\Hub\Application::statusColor($application->status)  }}">{{ \App\Models\Hub\Application::status($application->status) }}</div>
                                <h2 class="ml-5 dark:text-gray-300">{{ \App\Models\Hub\ApplicationCategory::name($application->application_category_id) }}
                                    <small class="text-gray-400 dark:text-gray-400">| {{ $application->updated_at->diffForHumans() }}</small>
                                </h2>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('applications.view', $application->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="h-6 w-auto text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($applications->isEmpty())
                    <div class="bg-white dark:bg-slate-600 rounded-xl p-2 mt-4">
                        <div class="p-2 dark:text-gray-300">
                            No Applications Submitted
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
