<x-app-layout>
    <!-- Announcement Settings with a content field and a colour picker -->
    <div class="p-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-10">
                <div class="flex justify-between">
                    <h1 class="text-xl font-bold dark:text-gray-300">Announcement Settings</h1>
                </div>
                <form method="POST" action="{{ route('hub.settings.announcement.patch') }}">
                    @csrf
                    @method('PATCH')

                    <input type="color" name="colour" id="colour">
                    <label for="colour">Choose a colour for the announcement bar</label>

                    {{ dd($announcement) }}

                    <div class="mt-4">
                        <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Content</label>
                        <textarea id="content" name="content" class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="This is my announcement content">{{ $announcement->announcement }}</textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
