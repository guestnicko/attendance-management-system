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
                <x-export :route="route('fines.export')" :events="$events" />
            </div>
            <div>
                <x-clear-logs :route="route('fines.clear')" :events="$events" />
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
                            <th scope="col" class="py-5 border border-green-800 text-center">Name</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Program</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Set</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Level</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Missed Actions</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Fine Amount</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Total Fines</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Event</th>
                            <th scope="col" class="py-5 border border-green-800 text-center">Date</th>
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
