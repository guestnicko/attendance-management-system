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
                <x-export :route="route('logs.export')" :events="$events" />
            </div>
            <div>
                <x-clear-logs :route="route('logs.clear-logs')" :events="$events" />
            </div>
        </div>
    </x-slot>


    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops!...",
                    html: `
                   <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside text-left">
                       @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                       @endforeach
                   </ul>
               `,
                    showConfirmButton: true,
                });
            });
        </script>
    @endif


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
