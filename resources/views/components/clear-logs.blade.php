@props(['route' => '', 'events' => ''])


<div x-data="{ open: false }" class="transition-all flex justify-end ">

    <button x-on:click="open = ! open"
        class="bg-gray-800 hover:bg-gray-700 text-white rounded-md text-md flex p-3 items-center">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
        </svg>

        Clear Logs
    </button>
    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div x-on:click.outside="open = false"
            class="max-w-[1000px] bg-gray-100 text-gray-950 p-6 rounded-lg shadow-lg min-w-[400px]">
            <div class="border-b-2 border-green-500 mb-5 ">
                {{-- MODAL HEADER --}}
                <h1 class="text-2xl font-bold text-gray-900">
                    Clear Logs
                </h1>
            </div>
            <div class="mb-5 overflow-y-scroll max-h-[400px] min-h-[200px]">
                {{-- MODAL BODY --}}
                <form class="" method="POST" action="{{ $route }}" id="clearLogsForm">
                    @csrf
                    @if ($events != '')
                        <select name="event_id" id="clear_eventField"
                            class="block w-full text-lg text-gray-500 bg-transparent border-0 border-b-2 border-violet-500 appearance-none ">
                            <option value="" selected>Select Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                            @endforeach
                        </select>
                    @else
                        <p class="p-3">No Events Found</p>
                    @endif

                </form>
            </div>

            <div class="flex justify-end gap-4">
                {{-- MODAL FOOTER --}}
                <button x-on:click="open = ! open" onclick="clearLogs()"
                    class="bg-gray-800 hover:bg-gray-700 text-white rounded-md text-md flex p-3 items-center">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>

                    Delete Logs
                </button>
                <button x-on:click="open = false"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function clearLogs() {
        document.querySelector("#clearLogsForm").submit();
    }
</script>
