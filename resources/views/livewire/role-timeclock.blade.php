<div class="mt-5">
    <div class="flex gap-4">
        <div class="w-full">
            <label class="text-gray-400" for="role-dropdown">Role</label>
            <select name="role-dropdown" wire:model="selectedRole" class="block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-zinc-800 text-white border-zinc-700 hover:bg-zinc-600 rounded-md">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <label class="text-gray-400" for="time">Hours</label>
            <input type="number" name="time" id="time" wire:model="time" class="w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-zinc-800 text-white border-zinc-700 hover:bg-zinc-600 rounded-md">
        </div>
    </div>
    <button data-modal-target="promotionModal" data-modal-toggle="promotionModal" class="bg-purple-500 hover:bg-purple-600 text-white rounded-md px-4 py-2 mt-4 w-full cursor-pointer transition">View Promotions for {{ $this->selectedRoleName }}</button>
    <div class="mt-2">
        @if (session()->has('timeclock-success'))
            <span class="text-green-400 mt-2">{{ session('timeclock-success') }}</span>
        @endif

        @if (session()->has('timeclock-error'))
            <span class="text-red-400 mt-2">{{ session('timeclock-error') }}</span>
        @endif
    </div>

    {{-- Modal --}}
    <div id="promotionModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-zinc-800">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-zinc-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Eligible Users
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-zinc-700 hover:text-white rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="promotionModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-2">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400 font-bold">The following people are eligble for a promotion. The set hour requirement for a promotion is <span class="font-bold text-purple-400">{{ $time }} hours</span>.</p>

                    <table class="min-w-full divide-y divide-none">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700">
                                <span>Username</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium whitespace-nowrap uppercase tracking-wider bg-purple-400 text-gray-700">
                                <span>Time</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-zinc-700 divide-none">
                            @foreach($eligibleUsers as $user)
                                <tr class="bg-zinc-700 text-white">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                        {{ $user->name }} <span class="text-gray-400 text-xs">({{ $user->id }})</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                        {{ $user->time }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
