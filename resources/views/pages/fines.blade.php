<?php
$page = 'fines';
?>
<x-app-layout>

    @vite(['resources/js/fines.js'])
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
            {{ __('Fines Reports') }}
        </h2>
        <div class="flex justify-between mt-5">
            <div class="flex gap-3">

                <button onclick="GeneratePDFReport()"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md text-md flex p-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    Print PDF
                </button>
                <button onclick="GenerateExcelReport()"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md text-md flex p-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                    </svg>
                    Print Excel
                </button>
            </div>
            <div class="">
                <button class="bg-gray-800 hover:bg-gray-700 text-white rounded-md text-md flex p-3 items-center">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>

                    Clear Logs
                </button>
            </div>
        </div>
    </x-slot>

    {{-- FORMS FOR EXPORTING PDFS AND EXCELS --}}
    <form method="POST" action="{{ route('logs.export') }}" id="exportForm" hidden>
        @csrf
        <input type="text" name="s_program" id="programField">
        <input type="text" name="s_set" id="setField">
        <input type="text" name="s_lvl" id="lvlField">
        <input type="text" name="s_status" id="statusField">
        <input type="text" name="file_type" id="fileType">
        <input type="text" name="event_id" id="inputField">
    </form>

    {{-- Content --}}
    <div class="bg-white p-3 rounded-md">
        <div class="mt-4 overflow-x-auto shadow-md sm:rounded-lg">
            <h3 class="text-3xl text-gray-900 font-extrabold">
                Fines Record
            </h3>
            <div class="flex justify-between my-5 mx-1">
                <x-search :page="$page" :route="route('fetchFinesViaSearch')" />
                <div class="w-full flex items-center justify-end gap-5">
                    <x-event-filter :events="$events" :route="route('fetchFinesViaEvent')" />
                    <x-filter :page="$page" :route="route('fetchFinesViaCategory')" />
                </div>
            </div>


            {{-- Fines Table Section --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="min-w-full w-full text-lg text-center text-gray-900 font-semibold"">
                    <thead class="text-lg font-semibold text-gray-100 uppercase bg-green-700">
                        <tr">
                            <th scope="col" class="py-5">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Program</th>
                            <th scope="col">Set</th>
                            <th scope="col">Level</th>
                            <th scope="col">Missed Actions</th>
                            <th scope="col">Fine Amount</th>
                            <th scope="col">Total Fines</th>
                            <th scope="col">Event</th>
                            <th scope="col">Date</th>
                            </tr>
                    </thead>
                    <tbody id="student_table_body">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($logs as $fine)
                            <tr class="table_row shadow-lg border-3">
                                <td class="py-5">{{ $i++ }}</td>
                                <td>{{ $fine->s_fname . ' ' . $fine->s_lname }}</td>
                                <td>{{ $fine->s_program }}</td>
                                <td>{{ $fine->s_set }}</td>
                                <td>{{ $fine->s_lvl }}</td>
                                <td>
                                    <ul class="text-center text-red-700 font-mono text-base">
                                        @if ($fine->morning_checkIn_missed)
                                            <li>Morning Check-in</li>
                                        @endif
                                        @if ($fine->morning_checkOut_missed)
                                            <li>Morning Check-out</li>
                                        @endif
                                        @if ($fine->afternoon_checkIn_missed)
                                            <li>Afternoon Check-in</li>
                                        @endif
                                        @if ($fine->afternoon_checkOut_missed)
                                            <li>Afternoon Check-out</li>
                                        @endif
                                    </ul>
                                </td>
                                <td>₱{{ $fine->fines_amount ? number_format($fine->fines_amount, 2) : '-' }}</td>
                                <td>₱{{ $fine->total_fines ? number_format($fine->total_fines, 2) : '-' }}</td>
                                <td>{{ $fine->event_name ? $fine->event_name : '-' }}</td>
                                <td>{{ $fine->created_at ? date('M d, Y', strtotime($fine->created_at)) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span id="std_info_table" class="py-5">

                </span>
            </div>
            <x-pagination :count="$pageCount" :lastpage="$logs->lastPage()" />
        </div>


</x-app-layout>
