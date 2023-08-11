<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Player Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("View your player statistics on the Lynus.GG FiveM Server.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @if($record['status'])
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full">
                <div class="bg-amber-600 px-5 py-8 rounded-xl font-bold text-md text-gray-300 flex items-center gap-4 w-full">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white text-xl">{{ $record['data']['playerWarns'] }}</p>
                        @if($record['data']['playerWarns'] > 1 || $record['data']['playerWarns'] < 1)
                            Warnings
                        @else
                            Warning
                        @endif
                    </div>
                </div>
                <div class="bg-yellow-400 px-5 py-8 rounded-xl font-bold text-md text-gray-300 flex items-center gap-4 w-full">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12">
                            <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 011.032 0 11.209 11.209 0 007.877 3.08.75.75 0 01.722.515 12.74 12.74 0 01.635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 01-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 01.722-.516l.143.001c2.996 0 5.718-1.17 7.734-3.08zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zM12 15a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75v-.008a.75.75 0 00-.75-.75H12z" clip-rule="evenodd" />
                        </svg>

                    </div>
                    <div>
                        <p class="text-white text-xl">{{ $record['data']['playerKicks'] }}</p>
                        @if($record['data']['playerKicks'] > 1 || $record['data']['playerKicks'] < 1)
                            Kicks
                        @else
                            Kick
                        @endif
                    </div>
                </div>
                <div class="bg-red-500 px-5 py-8 rounded-xl font-bold text-md text-gray-300 flex items-center gap-4 w-full">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12">
                            <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 011.032 0 11.209 11.209 0 007.877 3.08.75.75 0 01.722.515 12.74 12.74 0 01.635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 01-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 01.722-.516l.143.001c2.996 0 5.718-1.17 7.734-3.08zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zM12 15a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75v-.008a.75.75 0 00-.75-.75H12z" clip-rule="evenodd" />
                        </svg>

                    </div>
                    <div>
                        <p class="text-white text-xl">{{ $record['data']['playerBans'] }}</p>
                        @if($record['data']['playerBans'] > 1 || $record['data']['playerBans'] < 1)
                            Bans
                        @else
                            Ban
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div class="bg-green-500 px-5 py-8 rounded-xl font-bold text-md text-gray-300 flex items-center gap-4 w-full">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white text-xl">{{ $record['data']['playerTrustscore'] }}%</p>
                        Trustscore
                    </div>
                </div>
                <div class="bg-green-500 px-5 py-8 rounded-xl font-bold text-md text-gray-300 flex items-center gap-4 w-full">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12">
                            <path d="M7.493 18.75c-.425 0-.82-.236-.975-.632A7.48 7.48 0 016 15.375c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23h-.777zM2.331 10.977a11.969 11.969 0 00-.831 4.398 12 12 0 00.52 3.507c.26.85 1.084 1.368 1.973 1.368H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 01-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white text-xl">{{ $record['data']['playerCommends'] }}</p>
                        @if($record['data']['playerCommends'] > 1 || $record['data']['playerCommends'] < 1)
                            Commends
                        @else
                            Commend
                        @endif
                    </div>
                </div>
            </div>
            <hr class="h-px my-8 mt-2 mb-2 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Your Punishments') }}
                </h2>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Reason
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Staff
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($record['data']['warnRecord'] as $warn)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Warn</span>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $warn['message'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $warn['staff'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $warn['date'] }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach($record['data']['kickRecord'] as $warn)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Warn</span>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $warn['message'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $warn['staff'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $warn['date'] }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach($record['data']['banRecord'] as $warn)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Warn</span>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $warn['message'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $warn['staff'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $warn['date'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Your Achievements') }}
                </h2>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Reason
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Staff
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($record['data']['commendRecord'] as $warn)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Commend</span>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $warn['message'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $warn['staff'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $warn['date'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <p class="dark:text-gray-400">There was an issue when trying to gather your player information. Please try again later. If you are still experiencing this issue, please contact a member of staff.</p>
        @endif
    </div>
</section>
