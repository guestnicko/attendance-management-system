<?php
$page = 'logs';
?>
<x-app-layout>

    @vite(['resources/js/logs.js'])
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
            {{ __('Activity Reports') }}
        </h2>
        <div class="flex justify-between mt-5">
            <div class="flex gap-3">
                <button onclick="GeneratePDFReport()"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md p-3 text-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    Print PDF
                </button>
                <button onclick="GenerateExcelReport()"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md p-3 text-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                    </svg>
                    Print Excel
                </button>

            </div>
            <div>
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
                Attendance Record
            </h3>
            <div class="flex justify-between my-5 mx-1">
                <div class="w-full">
                    {{-- Search Form --}}
                    <x-search :page="$page" :route="route('fetchLogViaSearch')" />
                </div>
                <div class="w-full flex items-center justify-end gap-5">
                    <x-event-filter :events="$events" :route="route('fetchLogViaEvent')" />

                    <x-filter :route="route('fetchLogViaCategory')" />

                </div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="min-w-full w-full text-lg text-center text-gray-900 font-semibold">
                    <thead class="text-lg font-semibold text-gray-100 uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="py-5">No.</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Name</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Program</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Set</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Level</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Morning Time In</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Morning Time Out</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Afternoon Time In</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Afternoon Time Out</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Event</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody id="student_table_body">
                        {{-- Removed Hard-coded data --- This is now ready for dynamic data --}}
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($logs as $log)
                            <tr class="table_row shadow-lg border-3">
                                <td class="py-5">{{ $i++ }}</td>
                                <td>{{ $log->s_fname . ' ' . $log->s_lname }} </td>
                                <td>{{ $log->s_program }}</td>
                                <td>{{ $log->s_set }}</td>
                                <td>{{ $log->s_lvl }}</td>

                                {{-- Morning Attendances --}}
                               <td>
                                @if ($log->attend_checkIn)
                                    {{ date('h:i A', strtotime($log->attend_checkIn)) }}
                                @else
                                    <span class="text-red-500">Absent</span>
                                @endif
                               </td>
                               <td>
                                @if ($log->attend_checkOut)
                                    {{ date('h:i A', strtotime($log->attend_checkOut)) }}
                                @else
                                    <span class="text-red-500">Absent</span>
                                @endif
                               </td>
                                {{-- Afternoon Attendances --}}
                               <td>
                                @if ($log->attend_afternoon_checkIn)
                                    {{ date('h:i A', strtotime($log->attend_afternoon_checkIn)) }}
                                @else
                                    <span class="text-red-500">Absent</span>
                                @endif
                               </td>
                               <td>
                                @if ($log->attend_afternoon_checkOut)
                                    {{ date('h:i A', strtotime($log->attend_afternoon_checkOut)) }}
                                @else
                                    <span class="text-red-500">Absent</span>
                                @endif
                               </td>
                                <td>{{ $log->event_name }}</td>
                                <td>{{ $log->date ? date('M d, Y', strtotime($log->date)) : '---' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span id="std_info_table"></span>
            </div>

        </div>

        <x-pagination :count="$pageCount" :lastpage="$logs->lastPage()" />
    </div>

</x-app-layout>
