@props(['route' => '', 'events' => ''])



<div x-data="{ open: false }" class="transition-all flex justify-end ">

    <button x-on:click="open = ! open"
        class="bg-green-600 hover:bg-green-700 text-white rounded-md p-3 text-md flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
        </svg>
        Export Logs
    </button>
    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div x-on:click.outside="open = false"
            class="max-w-[1000px] bg-gray-100 text-gray-950 p-6 rounded-lg shadow-lg">
            <div class="border-b-2 border-green-500 mb-5 ">
                <h1 class="text-2xl font-bold text-gray-900">
                    Export Logs
                </h1>
            </div>
            <div class="mb-5 overflow-y-scroll max-h-[400px]">
                {{-- FORMS FOR EXPORTING PDFS AND EXCELS --}}
                <form id="exportForm" method="POST" action="{{ $route }}">
                    @csrf
                    <div class="form-group">
                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Prepared By:
                        </label>
                        <input type="text" id="" name="prepared_by"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="form-group">
                        <label for="">Events</label>
                        @if ($events != '')
                            <select name="event_id" id="eventFieldExport"
                                onchange="fetchLogsByEvent('{{ $route }}')"
                                class="block w-full text-lg text-gray-500 bg-transparent border-0 border-b-2 border-violet-500 appearance-none ">
                                <option value="" selected>Select Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                        @else
                            <p class="p-3">No Events Found</p>
                        @endif
                    </div>
                    <!-- Dropdown menu -->
                    <div class="z-10 w-auto p-3 bg-white rounded-lg shadow dark:bg-gray-700 border-2">
                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                            Category
                        </h6>
                        {{-- List for Program --}}
                        <div class="flex justify-between gap-3">
                            <div class="">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold ">Program</label>
                                    @foreach (['BSIT', 'BSIS'] as $program)
                                        <li class="flex items-center">
                                            <input value="{{ $program }}" type="checkbox" name="program"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $program }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $program }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- List for Year Levels --}}
                            <div class="">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold ">Year Level</label>
                                    {{-- Key-value pair for this list, key is for the database field, value is the placeholder --}}
                                    @foreach (['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'] as $key => $value)
                                        <li class="flex items-center">
                                            <input type="checkbox" value="{{ $key }}" name="lvl"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $key }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $value }}
                                            </label>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            {{-- List for Sets --}}
                            <div class="">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold ">Set</label>
                                    @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $set)
                                        <li class="flex items-center">
                                            <input value="{{ $set }}" type="checkbox" name="set"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $set }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $set }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- List for Status if Enrolled, Dropped, or Graduated --}}
                            <div class="">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold ">Status</label>
                                    @foreach (['ENROLLED', 'DROPPED', 'GRADUATED', 'TO BE UPDATED'] as $status)
                                        <li class="flex items-center">
                                            <input value="{{ $status }}" type="checkbox" name="status"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $status }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $status }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>




                    </div>
                    <input type="text" name="file_type" id="file_type" hidden>
                </form>
            </div>

            <div class="flex justify-end gap-4">
                <button onclick="generateReport('{{ $route }}', 'excel')"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md p-3 text-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                    </svg>
                    Print Excel
                </button>
                <button onclick="generateReport('{{ $route }}', 'pdf')"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md p-3 text-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    Print PDF
                </button>
                <button x-on:click="open = false"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function generateReport(uri, type) {

        // Fetch all fields
        const program = document.querySelectorAll(
            '#search_program input[name="program"]:checked'
        );
        const lvl = document.querySelectorAll(
            '#search_lvl input[name="lvl"]:checked'
        );
        const set = document.querySelectorAll(
            '#search_set input[name="set"]:checked'
        );
        const status = document.querySelectorAll(
            '#search_status input[name="status"]:checked'
        );
        const program_data = Array.from(program).map((cb) => cb.value);
        const lvl_data = Array.from(lvl).map((cb) => cb.value);
        const set_data = Array.from(set).map((cb) => cb.value);
        const status_data = Array.from(status).map((cb) => cb.value);
        const event_id = document.querySelector("#eventFieldExport").value;
        document.querySelector("#file_type").value = type;
        document.querySelector("#exportForm").submit();

    }
</script>
