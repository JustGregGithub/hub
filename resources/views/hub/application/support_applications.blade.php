<x-app-layout>
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <h1 class="text-xl font-bold dark:text-gray-300">Application Support / {{ $category->name }}</h1>
                <div class="bg-neutral-800 rounded-xl p-4 mt-4 flex flex-col gap-4">
                    <span class="font-bold dark:text-gray-300">Category Statistics</span>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach(\App\Models\Hub\Application::STATUSES as $key=>$value)
                            <div class="bg-{{ \App\Models\Hub\Application::statusColor($value) }} text-center p-6 rounded-md">
                                <p class="text-3xl text-white font-extrabold">{{ $category->statusCount($value) }}</p>
                                <p class="text-xl text-gray-200">{{$key}}</p>
                            </div>
                        @endforeach
                    </div>

                    @foreach (\App\Models\Hub\Application::STATUSES as $key=>$value)
                        <div class="bg-neutral-700 rounded-md p-4">
                            <span class="font-bold dark:text-gray-300">{{ $key }} Applications ({{ $category->statusCount($value) }})</span>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-700 dark:text-gray-200">
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
                                            <tr>
                                                <th scope="row"
                                                    class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                                    {{ $category->name }}
                                                </th>
                                                <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                                    {{ \App\Models\User::find($application->user_id)->displayName() }}
                                                </td>
                                                <td class="px-6 py-4 bg-neutral-900 font-medium whitespace-nowrap text-{{ \App\Models\Hub\Application::statusColor($application->status) }}">
                                                    {{ \App\Models\Hub\Application::status($application->status) }}
                                                </td>
                                                <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                                    {{ $application->updated_at->diffForHumans() }}
                                                </td>
                                                <td class="bg-neutral-900 px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                                    <a href="{{ route('applications.view', $application->id) }}"
                                                       class="font-medium bg-blue-500 hover:bg-blue-600 transition text-white rounded-md px-4 py-2 dark:text-gray-200">View</a>
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
