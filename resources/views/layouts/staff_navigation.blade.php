@php
    $serverId = request()->segment(2);
    $servers = \App\Models\Staff\Server::all();
@endphp

<div class="w-full bg-zinc-900">
    <button data-drawer-target="separator-sidebar" data-drawer-toggle="separator-sidebar"
            aria-controls="separator-sidebar" type="button"
            class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                  d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>
</div>
<aside id="separator-sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 overflow-y-hidden"
       aria-label="Sidebar">
    <div class="h-full py-4 overflow-y-auto bg-zinc-900 flex flex-col justify-between">
        <div>
            <img src="/images/lynus.webp" alt="LynusRP Logo" class="h-32 m-auto">
            <div class="mt-8">
                <ul class="font-medium group cursor-pointer">
                    <li class="
                        @if (Request::is('staff'))
                            bg-purple-600
                        @endif
                        pr-2 group-hover:bg-purple-600
                        ">
                        <a href="{{ route('staff.home') }}" class="
                            flex items-center p-4 group-hover:bg-purple-400 group-hover:text-white text-gray-300
                            @if (Request::is('staff'))
                                bg-purple-400 text-white
                            @endif
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>

                            <span class="ml-3">Home</span>
                        </a>
                    </li>
                </ul>
                <ul class="font-medium group cursor-pointer">
                    <li class="group-hover:bg-purple-600 pr-2
                        @if (Str::startsWith(request()->path(), 'staff/'.$serverId))
                            bg-purple-600
                        @endif
                        " aria-controls="dropdown-servers" data-collapse-toggle="dropdown-servers">
                        <div class="
                            flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                            @if (Str::startsWith(request()->path(), 'staff/'.$serverId))
                                bg-purple-400 text-white
                            @endif
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>
                            </svg>

                            <span class="ml-3">
                                Servers
                            </span>
                            <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </div>
                    </li>
                </ul>
                <ul class="hidden ml-5">
                    <li class="group-hover:bg-purple-600 pr-2
                        @if (Str::startsWith(request()->path(), 'staff/'.$serverId))
                            bg-purple-600
                        @endif
                        " aria-controls="dropdown-staff" data-collapse-toggle="dropdown-staff">
                        <div class="
                            flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                            @if (Str::startsWith(request()->path(), 'staff/'.$serverId))
                                bg-purple-400 text-white
                            @endif
                        ">
                            <span class="ml-3">
                                Servers
                            </span>
                            <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </div>
                    </li>
                </ul>

                <ul id="dropdown-servers" class="hidden ml-5">
                    @foreach($servers as $key => $server)
                        <ul id="dropdown-servers-{{ $key }}" class="font-medium group cursor-pointer ml-5">
                            <li class="group-hover:bg-purple-600 pr-2
                                @if (Request::is('staff/' . $server->id . '/*') && !Request::is('staff/' . $server->id . '/permissions') && !Request::is('staff/' . $server->id . '/timeclock'))
                                    bg-purple-700
                                @endif
                            " aria-controls="dropdown-servers-{{ $key }}-options" data-collapse-toggle="dropdown-servers-{{ $key }}-options">
                                <div class="
                                    flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                                @if (Request::is('staff/' . $server->id . '/*') && !Request::is('staff/' . $server->id . '/permissions') && !Request::is('staff/' . $server->id . '/timeclock'))
                                        bg-purple-500 text-white
                                    @endif
                                ">
                                    <span class="ml-3">
                                        {{ $server->name }}
                                    </span>
                                    <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m1 1 4 4 4-4"/>
                                    </svg>
                                </div>
                            </li>
                        </ul>
                        <ul id="dropdown-servers-{{ $key }}-options" class="hidden ml-5">
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                                    </svg>
                                    <span class="ml-3">Statistics</span>
                                </a>
                            </li>
                            <li class="flex justify-center">
                                <hr class="border-zinc-700 w-5/6">
                            </li>
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server.search', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>

                                    <span class="ml-3">Quick Search</span>
                                </a>
                            </li>
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server.players', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                    <span class="ml-3">Players</span>
                                </a>
                            </li>
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server.chats', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                    </svg>
                                    <span class="ml-3">Chats</span>
                                </a>
                            </li>
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server.deaths', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    <span class="ml-3">Deaths</span>
                                </a>
                            </li>
                            <li class="group hover:bg-purple-600 pr-2">
                                <a href="{{ route('staff.server.reports', $server) }}"
                                   class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
                                    </svg>
                                    <span class="ml-3">Reports</span>
                                </a>
                            </li>
                        </ul>
                    @endforeach
                </ul>

                {{-- Staff Dropdown --}}
                <ul class="font-medium group cursor-pointer">
                    <li class="group-hover:bg-purple-600 pr-2
                        @if (Str::startsWith(request()->path(), 'staff/'.$serverId.'/permissions') || Str::startsWith(request()->path(), 'staff/'.$serverId.'/timeclock'))
                            bg-purple-600
                        @endif
                        " aria-controls="dropdown-staff" data-collapse-toggle="dropdown-staff">
                        <div class="
                            flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                            @if (Str::startsWith(request()->path(), 'staff/'.$serverId.'/permissions') || Str::startsWith(request()->path(), 'staff/'.$serverId.'/timeclock'))
                                bg-purple-400 text-white
                            @endif
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>

                            <span class="ml-3">
                                Staff
                            </span>
                            <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </div>
                    </li>
                </ul>
                <ul id="dropdown-staff" class="hidden ml-5">
                    @foreach($servers as $key => $value)
                        @can('can-view-timeclock', $value)
                            <ul id="dropdown-staff-{{ $key }}" class="font-medium group cursor-pointer ml-5">
                                <li class="group-hover:bg-purple-600 pr-2
                                    @if (Request::is('staff/permissions/' . $value->id))
                                        bg-purple-600
                                    @endif
                                " aria-controls="dropdown-staff-{{ $key }}-options" data-collapse-toggle="dropdown-staff-{{ $key }}-options">
                                    <div class="flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                                        @if (Request::is('staff/permissions/' . $value->id))
                                            bg-purple-400 text-white
                                        @endif
                                    ">
                                        <span class="ml-3">
                                            {{ $value->name }}
                                        </span>
                                        <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                             fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                        </svg>
                                    </div>
                                </li>
                            </ul>
                        @endcan
                        @can('can-manage-roles', $value)
                            <ul id="dropdown-staff-{{ $key }}-options" class="hidden ml-5">
                                <li class="group hover:bg-purple-600 pr-2">
                                    <a href="{{ route('staff.server.timeclock', $value->id) }}"
                                       class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="ml-3">Timeclock</span>
                                    </a>
                                </li>
                                <li class="group hover:bg-purple-600 pr-2">
                                    <a href="{{ route('staff.permissions', $value->id) }}"
                                       class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                        </svg>
                                        <span class="ml-3">Permissions</span>
                                    </a>
                                </li>
                            </ul>
                        @endcan
                    @endforeach
                </ul>

                {{-- Settings Dropdown --}}
                <ul class="font-medium group cursor-pointer">
                    <li class="group-hover:bg-purple-600 pr-2" aria-controls="dropdown-settings"
                        data-collapse-toggle="dropdown-settings">
                        <div class="
                        flex items-center p-4 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition
                        @if (Request::is('home'))
                            bg-gray-100 dark:bg-gray-700
                        @endif
                    ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>

                            <span class="ml-3">
                                Settings
                            </span>
                            <svg class="w-3 h-3 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </div>
                    </li>
                </ul>
                <ul id="dropdown-settings" class="hidden ml-5">
                    <li class="group hover:bg-purple-600 pr-2">
                        <a href="#"
                           class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="ml-3">Panel Settings</span>
                        </a>
                    </li>
                    <li class="group hover:bg-purple-600 pr-2">
                        <a href="{{ route('staff.settings.servers') }}"
                           class="flex items-center w-full p-2 text-gray-300 group-hover:text-white group-hover:bg-purple-400 transition pl-11 group hover:bg-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="ml-3">Server Settings</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>

        <!-- Move this ul to the bottom -->
        <ul class="pt-4 mt-4 font-medium border-t border-gray-700">
            <li class="flex items-center justify-center gap-2">
                <!-- Logout form code -->
                <form method="POST" action="{{ route('logout') }}"
                      onclick="event.preventDefault(); this.closest('form').submit();" class="group cursor-pointer">
                    @csrf
                    <div class="bg-gray-100 dark:bg-gray-500 mr-3 rounded-md p-2 group-hover:bg-purple-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6 group-hover:text-purple-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </div>
                </form>
                <div class="flex items-center">
                    @if(Auth::user()->avatar)
                        <img class="h-8 w-8 rounded-full object-cover mr-2"
                             src="https://cdn.discordapp.com/avatars/{{ Auth::user()->id }}/{{ Auth::user()->avatar }}.webp"
                             alt="{{ Auth::user()->getTagAttribute() }}"/>
                    @endif
                    <a href="" class="transition hover:text-purple-700 text-gray-400">
                        {{ Auth::user()->displayName() }}
                    </a>
                </div>
            </li>
        </ul>
    </div>
</aside>
