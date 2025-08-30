<x-app-layout>
    @vite(['resources/js/student_attendance.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('preload-attendance')) {
                Swal.fire({
                    title: "Auto-attendance is on",
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
            sessionStorage.setItem('preload-attendance', 'true');
        });
    </script>

    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight mb-3">
            {{ __('Student Attendance') }}
        </h2>
        <div class="flex justify-between">
            <div class="">
                <h2 class="text-2xl font-bold text-gray-800">

                    @if ($event)
                    <span class="text-gray-600">
                        Event name: {{ $event->event_name }}
                    </span>
                    @else
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                title: "Oops!",
                                text: "There are no events yet!",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        });
                    </script>
                    <span class="text-red-600">
                        There are no event details yet
                    </span>
                    @endif

                </h2>
            </div>
            <div class="">
                <kbd
                    class="px-2 py-1.5 text-xs font-semibold text-green-500 bg-gray-900 border border-gray-900 rounded-lg">
                    Created at:
                    <span>
                        @if ($event)
                        <span class="text-yellow-500">
                            {{ date('Y-m-d, h:i A', strtotime($event->date)) }}
                        </span>
                        @else
                        <span class="text-yellow-500">
                            No Event
                        </span>
                        @endif
                    </span>
                </kbd>
            </div>
        </div>
        <div class="flex justify-between items-start p-4 mt-3 bg-gray-900 border-b rounded-md">
            <div>
                @if ($event)

                <div class="py-1">
                    <div class="block">
                        <h2 class="text-lg font-bold text-gray-100">
                            Orientation:
                            <span class="text-gray-200 font-light">
                                {{-- Line by Panzerweb: fixed proper message --}}
                                {{ $event->isWholeDay != 'false' ? 'Wholeday' : 'Half-Day' }}
                            </span>
                        </h2>
                    </div>
                    @if ($event->isWholeDay != 'false')
                    <h2
                        class="bg-yellow-100 text-yellow-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-yellow-300 border border-yellow-300">
                        Morning
                    </h2>
                    @endif
                    <div class="block">
                        {{-- Line by Panzerweb --}}

                        <h2 class="text-md font-bold text-gray-100">
                            Check In:
                            <span class="text-gray-200 font-light">
                                {{ date_format(date_create($event->checkIn_start), 'h:i A') }}
                            </span>
                            -
                            <span class="text-gray-200 font-light">
                                {{ date_format(date_create($event->checkIn_end), 'h:i A') }}
                            </span>
                        </h2>
                    </div>
                    <div class="block">
                        <h2 class="text-md font-bold text-gray-100">
                            Check Out:
                            <span class="text-gray-200 font-light">
                                {{ date_format(date_create($event->checkOut_start), 'h:i A') }}
                            </span>
                            -
                            <span class="text-gray-200 font-light">
                                {{ date_format(date_create($event->checkOut_end), 'h:i A') }}
                            </span>
                        </h2>
                    </div>
                    {{-- Code by Panzerweb: added afternoon time-in/outs details --}}
                    {{-- If Whole day --- then show timein/outs --}}
                    @if ($event->isWholeDay != 'false')
                    <h2
                        class="bg-yellow-100 text-yellow-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-yellow-300 border border-yellow-300">
                        Afternoon
                    </h2>
                    <h2 class="text-md font-bold text-gray-100">
                        Check In:
                        <span class="text-gray-200 font-light">
                            {{ date_format(date_create($event->afternoon_checkIn_start), 'h:i A') }}
                        </span>
                        -
                        <span class="text-gray-200 font-light">
                            {{ date_format(date_create($event->afternoon_checkIn_end), 'h:i A') }}
                        </span>
                    </h2>
                    <h2 class="text-md font-bold text-gray-100">
                        Check Out:
                        <span class="text-gray-200 font-light">
                            {{ date_format(date_create($event->afternoon_checkOut_start), 'h:i A') }}
                        </span>
                        -
                        <span class="text-gray-200 font-light">
                            {{ date_format(date_create($event->afternoon_checkOut_end), 'h:i A') }}
                        </span>
                    </h2>
                    @endif

                </div>
                @endif


            </div>

            @if ($event)
            <div x-data="{ play: false }" class="flex">
                <div x-data="{ open: false }" class="transition-all">
                    <div class="flex flex-col justify-end">
                        <button x-on:click="open = ! open" onclick="myFunction()"
                            class="bg-green-500 px-3 py-2 mb-2 hover:bg-green-700 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                            <div class="flex gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>

                                RFID/Student ID
                            </div>
                        </button>
                        <button onclick="startInterval()"
                            class="bg-yellow-500 px-3 py-2 mb-2 hover:bg-yellow-400 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                            <div class="flex gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59" />
                                </svg>

                                Start Attendance
                            </div>
                        </button>
                    </div>

                    <div x-show.important="open"
                        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                        <div id="modalAttendance" x-on:click.outside="open = false"
                            class="max-w-[1000px] min-w-[500px] bg-white p-6 rounded-lg shadow-lg">
                            <div class="border-b-2 border-gray-300 mb-5">
                                <h1 class="text-2xl font-bold">
                                    Attendance is Starting
                                </h1>
                            </div>
                            <div class="mb-5">
                                <form id="attendanceForm" method="POST" action="{{ route('attendanceStudent') }}">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="hidden" name="uri" value="{{ route('attendanceStudent') }}">
                                    <div class="flex flex-col">
                                        <label class="text-lg font-semibold" for="">
                                            Enter ID:
                                            <span class="text-md text-gray-500 mt-1">(Student ID/RFID)</span>
                                        </label>
                                        <input type="text" name="s_rfid" id="inputField" autocomplete="off">
                                    </div>
                                </form>
                            </div>

                            <div class="flex justify-end">
                                <button x-on:click="open = false" onclick="stopAttendance()"
                                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            @endif

        </div>
    </x-slot>

    <div class="mt-4">
        <div class="flex justify-between mb-3">
            <h3 class="text-3xl text-gray-900 font-extrabold">
                Attendance Record
            </h3>
        </div>

        <!-- Modern Table Container -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Student Attendance Records
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-green-100 text-sm font-medium">Total Students: {{ $students ? count($students) : 0 }}</span>
                    </div>
                </div>
        </div>

        <!-- Add Filtering Controls -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="entriesPerPage" class="text-sm font-medium text-gray-700">Show:</label>
                <select id="entriesPerPage" onchange="changeEntriesPerPage(this.value)" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-600">entries</span>
            </div>

            <div class="flex items-center gap-2">
                <label for="searchAttendance" class="text-sm font-medium text-gray-700">Search:</label>
                        <input type="text" id="searchAttendance" placeholder="Search students..." onkeyup="filterAttendance()" class="border border-gray-300 rounded-md px-3 py-1 text-sm w-48">
                    </div>
                </div>
        </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Student Info
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Morning Session
                                </div>
                            </th>
                            @if ($event && $event->isWholeDay != 'false')
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Afternoon Session
                                </div>
                            </th>
                        @endif
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Date
                                </div>
                            </th>
                    </tr>
                </thead>
                    <tbody id="student_table_body" class="bg-white divide-y divide-gray-200">
                    @isset($students)
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <!-- Student Information Column -->
                            <td class="px-6 py-4 border-r border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(substr($student->s_fname, 0, 1) . substr($student->s_lname, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $student->s_fname . ' ' . $student->s_lname }}
                                        </p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                                {{ $student->s_program }}
                                            </span>
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
                                                Set {{ $student->s_set }}
                                            </span>
                                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full font-medium">
                                                Year {{ $student->s_lvl }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Morning Session Column -->
                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check In:</span>
                            @if ($student->attend_checkIn)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ date_format(date_create($student->attend_checkIn), 'h:i A') }}
                                        </span>
                            @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>
                            @endif
                                    </div>
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                            @if ($student->attend_checkOut)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ date_format(date_create($student->attend_checkOut), 'h:i A') }}
                                        </span>
                            @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>
                            @endif
                                    </div>
                                </div>
                        </td>

                            @if ($event && $event->isWholeDay != 'false')
                            <!-- Afternoon Session Column -->
                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check In:</span>
                            @if ($student->attend_afternoon_checkIn)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ date_format(date_create($student->attend_afternoon_checkIn), 'h:i A') }}
                                        </span>
                            @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>
                            @endif
                                    </div>
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                            @if ($student->attend_afternoon_checkOut)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ date_format(date_create($student->attend_afternoon_checkOut), 'h:i A') }}
                                        </span>
                            @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        @endif

                            <!-- Date Column -->
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm text-gray-900">
                                    {{ date_format(date_create($student->created_at), 'M d, Y') }}
                                </div>
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
        </div>

        <!-- Add Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">{{ $students ? count($students) : 0 }}</span> entries
            </div>
            <div class="flex items-center gap-2">
                <button onclick="previousPage()" id="prevBtn" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <div id="pageNumbers" class="flex items-center gap-1">
                    <!-- Page numbers will be generated here -->
                </div>
                <button onclick="nextPage()" id="nextBtn" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add JavaScript for Attendance Pagination and Filtering -->
        <script>
            let currentPage = 1;
            let entriesPerPage = 10;
            let filteredStudents = @json($students ?? []);
            let allStudents = @json($students ?? []);

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                updatePagination();
                displayStudents();
            });

            function changeEntriesPerPage(value) {
                entriesPerPage = parseInt(value);
                currentPage = 1;
                updatePagination();
                displayStudents();
            }

            function filterAttendance() {
                const searchTerm = document.getElementById('searchAttendance').value.toLowerCase();

                filteredStudents = allStudents.filter(student =>
                    student.s_fname.toLowerCase().includes(searchTerm) ||
                    student.s_lname.toLowerCase().includes(searchTerm) ||
                    student.s_program.toLowerCase().includes(searchTerm) ||
                    student.s_set.toLowerCase().includes(searchTerm) ||
                    student.s_lvl.toString().includes(searchTerm)
                );

                currentPage = 1;
                updatePagination();
                displayStudents();
            }

            function displayStudents() {
                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = startIndex + entriesPerPage;
                const studentsToShow = filteredStudents.slice(startIndex, endIndex);

                // Update table body
                updateTableBody(studentsToShow);

                // Update entry counts
                document.getElementById('startEntry').textContent = startIndex + 1;
                document.getElementById('endEntry').textContent = Math.min(endIndex, filteredStudents.length);
                document.getElementById('totalEntries').textContent = filteredStudents.length;
            }

            function updateTableBody(students) {
                const tbody = document.getElementById('student_table_body');
                tbody.innerHTML = '';

                if (students.length === 0) {
                    // Determine colspan based on whether it's a whole day event
                    const isWholeDay = {
                        {
                            $event ? ($event - > isWholeDay != 'false' ? 'true' : 'false') : 'false'
                        }
                    };
                    const colspan = isWholeDay ? 4 : 3;

                    tbody.innerHTML = `
                        <tr>
                            <td colspan="${colspan}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900">No students found</h3>
                                    <p class="text-sm text-gray-500">No students match your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                students.forEach(student => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition-colors duration-200';

                    // Determine if it's a whole day event
                    const isWholeDay = {
                        {
                            $event ? ($event - > isWholeDay != 'false' ? 'true' : 'false') : 'false'
                        }
                    };

                    row.innerHTML = `
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            ${student.s_fname.charAt(0).toUpperCase() + student.s_lname.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        ${student.s_fname} ${student.s_lname}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                            ${student.s_program}
                                        </span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
                                            Set ${student.s_set}
                                        </span>
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full font-medium">
                                            Year ${student.s_lvl}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Morning Attendance -->
                        <td class="px-6 py-4 text-center border-r border-gray-200">
                            <div class="space-y-2">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Check In:</span>
                            ${student.attend_checkIn ? 
                                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ${new Date(student.attend_checkIn).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                                        </span>` :
                                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>`
                                    }
                                </div>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                            ${student.attend_checkOut ? 
                                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ${new Date(student.attend_checkOut).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                                        </span>` :
                                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Absent
                                        </span>`
                                    }
                                </div>
                            </div>
                        </td>
                        
                        ${isWholeDay ? `
                            <!-- Afternoon Attendance -->
                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check In:</span>
                                ${student.attend_afternoon_checkIn ? 
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ${new Date(student.attend_afternoon_checkIn).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                                            </span>` :
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Absent
                                            </span>`
                                        }
                                    </div>
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                                ${student.attend_afternoon_checkOut ? 
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ${new Date(student.attend_afternoon_checkOut).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                                            </span>` :
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Absent
                                            </span>`
                                        }
                                    </div>
                                </div>
                            </td>
                        ` : ''}
                        
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900">
                                ${new Date(student.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </div>
                        </td>
                    `;

                    tbody.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredStudents.length / entriesPerPage);
                const pageNumbers = document.getElementById('pageNumbers');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');

                // Clear existing page numbers
                pageNumbers.innerHTML = '';

                // Generate page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = `px-3 py-2 text-sm font-medium border rounded-md ${
                        i === currentPage 
                            ? 'bg-blue-600 text-white border-blue-600' 
                            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'
                    }`;
                    pageBtn.onclick = () => goToPage(i);
                    pageNumbers.appendChild(pageBtn);
                }

                // Update button states
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
            }

            function goToPage(page) {
                currentPage = page;
                displayStudents();
                updatePagination();
            }

            function previousPage() {
                if (currentPage > 1) {
                    currentPage--;
                    displayStudents();
                    updatePagination();
                }
            }

            function nextPage() {
                const totalPages = Math.ceil(filteredStudents.length / entriesPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    displayStudents();
                    updatePagination();
                }
            }
        </script>
    </div>

    <form id="getAttendanceForm" hidden>
        <input type="text" id="getURI" value="{{ route('getAttendanceRecent') }}" hidden>
    </form>

    @if ($event)
    {{-- FOR AUTO ATTENDANCE --}}
    <form id="auto_attendanceForm" method="POST" class="fixed -z-10">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="uri" value="{{ route('attendanceStudent') }}">
        <input type="text" name="s_rfid" id="inputField1" class="bg-transparent border-none"
            autocomplete="off">
    </form>
    @endif

</x-app-layout>
<script>
    // Added Pop Ups from Sweet Alert2
    let startAttendance = false;

    const scannedData = document.getElementById("inputField");

    function myFunction() {
        console.log("attendance start");
        document.getElementById("inputField").focus();
        startAttendance = true;

        stopInterval();
    }

    function stopAttendance() {
        console.log("attendance stop");
        startAttendance = false;
        Swal.fire({
            icon: "warning",
            title: "Attendance Stopped!",
            showConfirmButton: false,
            timer: 1000
        });

        startInterval();
    }
</script>