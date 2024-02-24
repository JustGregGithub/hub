<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- TinyMCE -->
        <script src="/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="https://cdn.tiny.cloud/1/jyud8m73n0bb1gczkp4w6ji6om9gjfv5iqbqqsxzzgj9ygxh/tinymce/6/plugins.min.js" referrerpolicy="origin"></script>

        <!-- ChartJS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Confetti -->
        <script src="/js/confetti/confetti.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        @if(request()->is('staff*'))
            @include('layouts.staff_navigation')
        @else
            @include('layouts.navigation')
        @endif

        @if (Session::has('success'))
            <div id="toast-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500
                rounded-lg shadow
                @if(request()->is('staff*'))
                    bg-zinc-900
                @else
                    bg-white dark:text-gray-400 dark:bg-gray-700
                @endif
                " role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ml-3 text-sm font-normal">{{ Session::get('success')}}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5
                    @if(request()->is('staff*'))
                        bg-zinc-800 hover:bg-zinc-700
                    @else
                        bg-white text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700
                    @endif
                    rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8 transition" data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (Session::has('warning'))
            <div id="toast-warning" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500
                rounded-lg shadow
                @if(request()->is('staff*'))
                    bg-zinc-900
                @else
                    bg-white dark:text-gray-400 dark:bg-gray-700
                @endif
                " role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-800 dark:text-orange-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                    </svg>
                    <span class="sr-only">Warning icon</span>
                </div>
                <div class="ml-3 text-sm font-normal">{{ Session::get('warning')}}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5
                    @if(request()->is('staff*'))
                        bg-zinc-800 hover:bg-zinc-700
                    @else
                        bg-white text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700
                    @endif
                    rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8 transition" data-dismiss-target="#toast-warning" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div id="toast-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500
                rounded-lg shadow
                @if(request()->is('staff*'))
                    bg-zinc-900
                @else
                    bg-white dark:text-gray-400 dark:bg-gray-700
                @endif
                " role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ml-3 text-sm font-normal">{{ $error }}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5
                    @if(request()->is('staff*'))
                        bg-zinc-800 hover:bg-zinc-700
                    @else
                        bg-white text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700
                    @endif
                    rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8 transition" data-dismiss-target="#toast-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            @endforeach
        @endif

        <div class="min-h-screen
                @if(request()->is('staff*'))
                    bg-zinc-950
                @else
                    bg-neutral-950
                @endif
        ">
            <main class="sm:ml-64">
{{--                    <div class="w-full p-4 bg-green-800 text-center">--}}
{{--                        <small>--}}
{{--                            <b>--}}
{{--                                Our server is  now online. You are now able to access the server! We apologise for all the DDoS attacks.--}}
{{--                            </b>--}}
{{--                        </small>--}}
{{--                    </div>--}}
                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>


        {{-- Tailwind Colours Fix --}}
        <p hidden class="hidden bg-yellow-900 text-yellow-300 bg-blue-900 text-blue-300 hover:text-blue-500 bg-red-900 text-red-300 bg-green-900 text-green-300 text-orange-500 bg-orange-500 bg-orange-200 text-blue-500 bg-blue-500 text-green-500 bg-green-200 bg-green-500 text-red-500 bg-red-200 bg-red-500"></p>

        @livewireScripts
    </body>
</html>
