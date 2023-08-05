<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300">Application Support / {{ $category->name }}</h1>
                <div class="bg-white dark:bg-slate-600 rounded-xl p-4 mt-4 flex flex-col gap-4">
                    <span class="font-bold dark:text-gray-300">Category Statistics</span>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach(\App\Models\Application::STATUSES as $key=>$value)
                            <div class="bg-{{ \App\Models\Application::statusColor($value) }} text-center p-6 rounded-md">
                                <p class="text-3xl text-white font-extrabold">{{ $category->statusCount($value) }}</p>
                                <p class="text-xl text-gray-200">{{$key}}</p>
                            </div>
                        @endforeach
                    </div>

                    @foreach (\App\Models\Application::STATUSES as $key=>$value)
                        <div class="bg-gray-100 dark:bg-slate-500 rounded-md p-4">
                            <span class="font-bold dark:text-gray-300">{{ $key }} Applications ({{ $category->statusCount($value) }})</span>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Position
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Applicant
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Last Updated
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                            @if($application->status == $value)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $category->name }}
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ \app\Models\User::find($application->user_id)->displayName() }}
                                                    </td>
                                                    <td class="px-6 py-4 text-{{ App\Models\Application::statusColor($application->status) }}">
                                                        {{ App\Models\Application::status($application->status) }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $application->updated_at->diffForHumans() }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <a href="{{ route('applications.view', $application->id) }}" class="font-medium bg-blue-500 hover:bg-blue-600 transition text-white rounded-md px-4 py-2 dark:text-gray-200">View</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
