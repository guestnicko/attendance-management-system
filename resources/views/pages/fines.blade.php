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
                    text: '{{ session('
                                                                            success ') }}',
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
        <!-- Add Filtering Controls -->
        <div class="mb-4 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="entriesPerPage" class="text-sm font-medium text-gray-700">Show:</label>
                <select id="entriesPerPage" onchange="changeEntriesPerPage(this.value)"
                    class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-600">entries</span>
            </div>

            <div class="flex items-center gap-2">
                <label for="searchFines" class="text-sm font-medium text-gray-700">Search:</label>
                <input type="text" id="searchFines" placeholder="Search fines..." onkeyup="filterFines()"
                    class="border border-gray-300 rounded-md px-3 py-1 text-sm w-48">
            </div>
        </div>

        <div class="mt-6 bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Fines Record
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-red-100 text-sm font-medium">Total Fines: {{ count($logs) }}</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Student Info
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    Missed Actions
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Fine Details
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Event Details
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="student_table_body">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($logs as $fine)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <!-- Student Information Column -->
                                <td class="px-6 py-4 border-r border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($fine->s_fname, 0, 1) . substr($fine->s_lname, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $fine->s_fname . ' ' . $fine->s_lname }}
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                <span
                                                    class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                                    {{ $fine->s_program }}
                                                </span>
                                                <span
                                                    class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    Set {{ $fine->s_set }}
                                                </span>
                                                <span
                                                    class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    Year {{ $fine->s_lvl }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Missed Actions Column -->
                                <td class="px-6 py-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        @if (
                                            $fine->morning_checkIn_missed ||
                                                $fine->morning_checkOut_missed ||
                                                $fine->afternoon_checkIn_missed ||
                                                $fine->afternoon_checkOut_missed)
                                            <div class="space-y-1">
                                                @if ($fine->morning_checkIn_missed)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Morning Check-in
                                                    </span>
                                                @endif
                                                @if ($fine->morning_checkOut_missed)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Morning Check-out
                                                    </span>
                                                @endif
                                                @if ($fine->afternoon_checkIn_missed)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Afternoon Check-in
                                                    </span>
                                                @endif
                                                @if ($fine->afternoon_checkOut_missed)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Afternoon Check-out
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">No missed actions</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Fine Details Column -->
                                <td class="px-6 py-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                ₱{{ $fine->fines_amount ? number_format($fine->fines_amount, 2) : '-' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 font-bold">
                                                ₱{{ $fine->total_fines ? number_format($fine->total_fines, 2) : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Event Details Column -->
                                <td class="px-6 py-4 text-center">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                </svg>
                                                {{ $fine->event_name ? $fine->event_name : '-' }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $fine->created_at ? date('M d, Y', strtotime($fine->created_at)) : '-' }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :count="$pageCount" :lastpage="$logs->lastPage()" />



    </div>


</x-app-layout>
