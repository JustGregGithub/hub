<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                    <div class="flex justify-between items-center">
                        <h1 class="text-xl font-bold dark:text-gray-300">All Tickets <span class="font-normal text-sm text-gray-500 dark:text-gray-400">- Sorted by latest</span></h1>
                        <a href="{{ route('tickets.create')  }}" class="flex gap-2 text-white bg-green-500 p-2 rounded-xl">
                            Create
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-auto h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </a>
                    </div>
                        @foreach($tickets as $ticket)
                        <div class="bg-white dark:bg-slate-500 rounded-xl p-4 mt-4">
                            <div class="flex justify-between items-center">
                                <p class="bg-gray-100 dark:bg-slate-600 dark:text-gray-300 rounded-lg p-2 flex-shrink-0">#{{ $ticket->id }}</p>
                                <h2 class="truncate px-4 flex-shrink dark:text-gray-300">
                                    {{ Str::ucfirst($ticket->title) }}
                                </h2>

                                <div class="flex gap-4">
                                    <a href="{{ route('tickets.view', $ticket->slug) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-auto text-gray-400 dark:text-gray-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('tickets.pin.patch', $ticket->slug) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            @if ($ticket->pinned)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-auto h-6 text-red-500 hover:text-gray-400 hover:dark:text-gray-600 transition fill-current">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-auto h-6 text-gray-400 dark:text-gray-600 fill-current hover:text-red-500 transition">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-gray-400 dark:text-gray-300 text-sm mt-2">Updated: {{ $ticket->updated_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    <div class="flex flex-col items-center mt-4">
                        <!-- Help text -->
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $ticketsOnPage }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $totalTickets }}</span> Tickets
                        </span>
                        <!-- Buttons -->
                        <div class="inline-flex mt-2 xs:mt-0">
                            <a id="previous" href="{{ route('tickets.all', Request::segment(3) - 1) }}" class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-500 rounded-l hover:bg-gray-400 transition">
                                Previous
                            </a>
                            <a href="{{ route('tickets.all', (Int)Request::segment(3) + 1) }}" class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-500 rounded-r hover:bg-gray-400 transition">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script>
        const previous = document.getElementById('previous');
        const previousUrl = previous.href.split('/');

        if (previousUrl[5] <= 0) {
            previous.classList.add('hidden');
        }
    </script>
</x-app-layout>
