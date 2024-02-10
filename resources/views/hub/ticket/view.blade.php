<x-app-layout>
    <div class="p-12 max-w-7xl mx-auto">

        {{--  Heading --}}
        <h1 class="text-xl font-bold dark:text-gray-400"><span
                    class="text-gray-500 dark:text-gray-600">#{{ $ticket->id  }}</span> {{ $ticket->title }}</h1>

        {{-- Content --}}
        <div class="flex flex-wrap lg:flex-nowrap mt-4 gap-4">
            {{-- Left side --}}
            <div class="w-full lg:w-3/4">
                <div class="bg-neutral-800 rounded-xl p-4 whitespace-normal break-words text-white">
                    {!! $ticket->content !!}
                </div>
                <hr class="h-px my-8 mt-2 mb-2 bg-gray-200 border-0 dark:bg-gray-700">
                @foreach($ticket->replies as $reply)
                    @if ($reply->type == \App\Models\Hub\TicketReply::TYPE_NOTE)
                        @can('can-support', $ticket)
                            <div class="bg-red-900 border border-red-950 rounded-xl p-4 mt-4">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-xl object-cover mr-2"
                                         src="https://cdn.discordapp.com/avatars/{{ $reply->user_id }}/{{ $reply->user->avatar }}.webp"
                                         alt="{{ $reply->user->getTagAttribute() }}"/>
                                    <div class="flex justify-between w-full">
                                        <div>
                                            <p class="break-all">{{ $reply->user->displayName()  }} @if ($reply->user->discriminator != 0)
                                                    #{{ $reply->user->discriminator }}
                                                @endif</p>
                                            <p class="text-xs text-gray-400 break-all">{{ $reply->user_id  }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-400 break-all">
                                                Sent: {{ $reply->created_at->diffForHumans() }}</p>
                                            @if($reply->created_at != $reply->updated_at)
                                                <p class="text-xs text-gray-400 break-all">
                                                    Updated: {{ $reply->updated_at->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    {!! $reply->content !!}
                                    @if($reply->user->signature)
                                        <hr class="h-px my-8 mt-2 mb-2 border-0 bg-red-800">
                                        <i class="text-gray-400 text-sm">
                                            {{ $reply->user->signature }}
                                        </i>
                                    @endif
                                </div>
                            </div>
                        @endcan
                    @else
                        <div class="bg-neutral-800 border border-gray-800 rounded-xl p-4 mt-4 text-white">
                            <div class="flex items-center">
                                <img class="h-12 w-12 rounded-xl object-cover mr-2"
                                     src="https://cdn.discordapp.com/avatars/{{ $reply->user_id }}/{{ $reply->user->avatar }}.webp"
                                     alt="{{ $reply->user->getTagAttribute() }}"/>
                                <div class="flex justify-between w-full">
                                    <div>
                                        <div class="flex gap-2 items-center">
                                            <p class="break-all">{{ $reply->user->displayName()  }} @if ($reply->user->discriminator != 0)
                                                    #{{ $reply->user->discriminator }}
                                                @endif</p>
                                            @if($ticket->assigned_person == $reply->user_id)
                                                <div>
                                                    <span class="break-normal bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Assigned Member</span>
                                                </div>
                                            @endif
                                            @if($ticket->user_id == $reply->user_id)
                                                <div>
                                                    <span class="break-normal bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Ticket Creator</span>
                                                </div>
                                            @endif
                                            @if(array_search($reply->user_id, $allowed_users->pluck('id')->toArray()) !== false)
                                                <div>
                                                    <span class="break-normal bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">Ticket User</span>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-300 break-all">{{ $reply->user_id  }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400 break-all">
                                            Sent: {{ $reply->created_at->diffForHumans() }}</p>
                                        @if($reply->created_at != $reply->updated_at)
                                            <p class="text-xs text-gray-400 break-all">
                                                Updated: {{ $reply->updated_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                {!! $reply->content !!}
                                @if($reply->user->signature)
                                    <hr class="h-px my-8 mt-2 mb-2 border-0 bg-neutral-700">
                                    <i class="text-gray-300 text-sm">
                                        {!! $reply->user->signature !!}
                                    </i>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
                <form method="POST" action="{{ route('tickets.reply.post', $ticket->slug)  }}" class="mt-4">
                    @csrf
                    <textarea name="reply" cols="30" rows="10"></textarea>
                    <div class="flex gap-2">
                        @can('can-support', $ticket)
                            <select name="type"
                                    class="border border-gray-300 rounded-md text-sm mt-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Reply</option>
                                <option value="1">Note</option>
                            </select>
                        @endcan
                        <input type="submit"
                               class="bg-blue-600 hover:bg-blue-700 rounded-md p-2 text-white hover:text-white mt-2 w-full cursor-pointer transition"
                               value="Submit Reply">
                    </div>
                </form>
            </div>

            {{-- Right side --}}
            <div class="w-full lg:w-1/4">
                <div class="bg-neutral-800 text-white rounded-xl p-4 flex items-center">
                    <img class="h-8 w-8 border border-gray-600 rounded-full object-cover mr-2"
                         src="https://cdn.discordapp.com/avatars/{{ $ticket->user->id }}/{{ $ticket->user->avatar }}.webp"
                         alt="{{ Auth::user()->getTagAttribute() }}"/>
                    <div>
                        <p class="break-all">{{ $ticket->user->displayName()  }} @if ($ticket->user->discriminator != 0)
                                #{{ $ticket->user->discriminator }}
                            @endif</p>
                        <p class="text-xs text-gray-400 break-all">{{ $ticket->user->id  }}</p>
                    </div>
                </div>

                <div class="bg-neutral-800 dark:text-gray-300 w-full rounded-xl p-4 text-sm mt-2">
                    <div class="text-gray-400">Created At</div>
                    <small>{{ $created_at }}</small>

                    <div class="text-gray-400 mt-4">Last Updated</div>
                    <small>{{ $updated_at }}</small>

                    <div class="text-gray-400 mt-4">Category</div>
                    <small>{{ $ticket->category->name }}</small>

                    <div class="text-gray-400 mt-4">Status</div>
                    <small class="text-{{ \App\Models\Hub\Ticket::statusColor($ticket->status) }}">{{ \App\Models\Hub\Ticket::status($ticket->status) }}</small>

                    <div class="text-gray-400 mt-4">Priority</div>
                    <small class="text-{{ $ticket->priorityColor() }}">{{ $ticket->priority($ticket->priority) }}</small>

                    <div class="text-gray-400 mt-4">Assigned Person:</div>
                    <small>{{ $ticket->getAssigneeName() }} <span
                                class="text-gray-400">({{ $ticket->assigned_person }})</span></small>
                </div>

                @can('is-not-allowed-user', $ticket)
                    <div class="bg-neutral-800 w-full rounded-xl p-4 text-sm mt-2">
                        <span class="text-gray-400 mt-4">Ticket Visibility</span>

                        @if($allowed_users->count() > 0)
                            <form method="POST" action="{{ route('tickets.user.delete', $ticket) }}" class="flex gap-2">
                                @csrf
                                @method('DELETE')

                                <select name="userid"
                                        class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($allowed_users as $user)
                                        <option value="{{ $user->id }}">{{ $user->displayName() }}</option>
                                    @endforeach
                                </select>
                                <input type="submit" value="Remove"
                                       class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-fit mt-2 cursor-pointer">
                            </form>
                            <small class="text-gray-400 mt-2">Additional people who have access to this ticket.</small>
                            <hr class="mt-2 mb-2">
                        @endif
                        <form method="POST" action="{{ route('tickets.user.patch', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="userid" placeholder="Discord ID"
                                   class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500">
                            <input type="submit" value="Add Person"
                                   class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                        </form>
                    </div>
                @endcan

                {{-- Support Tools --}}
                @can('can-support', $ticket)
                    <div class="bg-neutral-800 w-full rounded-xl p-4 text-sm mt-2">
                        <span class="text-gray-400">Change Category</span>
                        <form method="POST" action="{{ route('tickets.update.post', $ticket->slug) }}"
                              class="flex flex-col">
                            @csrf
                            <select name="category"
                                    class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500">
                                @foreach($ticket->categories() as $category)
                                    <option value="{{ $category->id  }}"
                                            @if($category->id == $ticket->category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <span class="text-gray-400 mt-4">Change Status</span>
                            <select name="status"
                                    class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500">
                                @foreach(\App\Models\Hub\Ticket::STATUSES as $key=>$value)
                                    <option value="{{ $value  }}"
                                            @if($value == $ticket->status) selected @endif> {{ $key     }}</option>
                                @endforeach
                            </select>

                            <span class="text-gray-400 mt-4">Assign Ticket</span>
                            <input type="text" name="assigned_person" placeholder="Discord ID"
                                   class="border rounded-md text-sm w-full mt-2 bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500">

                            <input type="submit" value="Submit Changes"
                                   class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                        </form>
                        <hr class="h-px my-8 mt-4 mb-4 border-0 bg-gray-700">
                        <form method="POST" action="{{ route('tickets.update.post', $ticket->slug) }}">
                            @csrf

                            <input type="hidden" name="category" value="{{ $ticket->category_id }}"/>
                            <input type="hidden" name="status" value="{{ $ticket->status }}"/>
                            @if ($ticket->assigned_person)
                                <input type="hidden" name="assigned_person" value="-1"/>
                                <input type="submit" value="Quick Un-Assign"
                                       class="bg-red-500 hover:bg-red-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                            @else
                                <input type="hidden" name="assigned_person" value="{{ Auth::user()->id }}"/>
                                <input type="submit" value="Assign to Me"
                                       class="bg-green-500 hover:bg-green-400 transition rounded-md px-4 py-2 text-white w-full mt-2 cursor-pointer">
                            @endif
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <x-tiny-mce/>
</x-app-layout>
