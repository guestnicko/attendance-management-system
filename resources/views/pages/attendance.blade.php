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
            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem('preload-attendance')) {
                    Swal.fire({
                        title: "Auto-attendance is on",
                        timer: 1500,
                        showConfirmButton: false,
                    });
                }
                sessionStorage.setItem('preload-attendance', 'true');
            })
        });
    </script>

    <x-slot name="header">   
        <div class="flex justify-between">
            <div class="">
                <h2 class="font-semibold text-4xl text-gray-900 leading-tight mb-3">
                    {{ __('Student Attendance') }}
                </h2>
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
            <div class="flex flex-col gap-2">
                <x-clock></x-clock>
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

                        {{-- Add Student --}}
                        <div class="z-50">
                            <x-new-modal>
                                <x-slot name="button">
                                    <div class="flex py-2 px-4 items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                        <span class="text-sm">
                                            Student
                                        </span>
                                </x-slot>


                                <x-slot name="heading">
                                    Add Student Information
                                </x-slot>
                                <x-slot name="content">
                                    <form id="studentForm" action="{{ route('addStudent') }}" x-ref="studentForm"
                                        method="POST" enctype="multipart/form-data"
                                        class="flex items-center overflow-y-scroll max-h-[500px]">
                                        @csrf
                                        <div class="basis-3/4 justify-start">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                                <div class="grid grid-cols-1">
                                                    <label for="">
                                                        RFID
                                                    </label>
                                                    <input type="text" placeholder="Scan RFID" name="s_rfid"
                                                        id="s_rfid"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <label for="">Student ID:</label>
                                                    <input type="text" placeholder="Enter Student ID (Ex. 2023-00069)"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                        name="s_studentID" id="s_studentID">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 mt-5 mx-7">
                                                <label for="">First Name:</label>
                                                <input type="text" placeholder="Enter Firstname" name="s_fname"
                                                    id="s_fname"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            </div>
                                            <div class="grid grid-cols-1 mt-5 mx-7">
                                                <label for="">Last Name:</label>
                                                <input type="text" placeholder="Enter Lastname" name="s_lname"
                                                    id="s_lname"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                                <div class="grid grid-cols-1">
                                                    <label for="">Middle Name</label>
                                                    <input type="text" placeholder="Enter Middlename" name="s_mname"
                                                        id="s_mname"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <label for="">Suffix</label>
                                                    <input type="text" placeholder="Enter Suffix" name="s_suffix"
                                                        id="s_suffix"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                                                <div class="grid grid-cols-1">
                                                    <label for="">Program</label>
                                                    <select name="s_program" id="s_program"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option selected value="">Select Program</option>
                                                        <option value="BSIT">BSIT</option>
                                                        <option value="BSIS">BSIS</option>
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <label for="">Year Level</label>
                                                    <select name="s_lvl" id="s_lvl"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option selected value="">Select Year Level</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <label for="">Set</label>
                                                    <select name="s_set" id="s_set"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option selected value="">Select Set</option>
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="C">C</option>
                                                        <option value="D">D</option>
                                                        <option value="E">E</option>
                                                        <option value="F">F</option>
                                                        <option value="G">G</option>
                                                        <option value="H">H</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-data="{ image: '{{ asset('images/icons/default-image.svg') }}' }" class="basis-1/4 flex flex-col mt-5 items-center gap-5">
                                            <img id="uploadImage" class="max-w-1/2 w-100" :src="image"
                                                alt="">
                                            <input id="uploadFile" type="file" name="s_image" x-ref="imageFile"
                                                x-on:change="image = URL.createObjectURL($refs.imageFile.files[0])" hidden>
                                            <button x-on:click="$refs.imageFile.click()" type="button"
                                                class="bg-green-400 text-white px-3 py-2 text-xl">
                                                Upload Image
                                            </button>
                                        </div>
                                    </form>
                                </x-slot>
                                <x-slot name="footer">
                                    <button onclick="testStudentForm()"
                                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                                        Test Form </button>
                                    <button x-on:click="$refs.studentForm.submit()"
                                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                                        Save </button>
                                </x-slot>
                            </x-new-modal>
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

        {{-- Code by Panzerweb: added afternoon time-in/outs in table columns --}}
        <div class="relative max-h-[400px] overflow-y-auto shadow-md sm:rounded-lg">
            <table
                class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold border-collapse">
                <thead class="sticky top-0 z-10 text-lg font-semibold text-gray-100 uppercase bg-green-700">
                    <tr>
                        <td class="py-5">Name</td>
                        <td class="py-5">Program</td>
                        <td class="py-5">Set</td>
                        <td class="py-5">Year Level</td>

                        @if ($event != null && $event->isWholeDay != 'false')
                            <td class="py-5">Morning Time In</td>
                            <td class="py-5">Morning Time Out</td>
                            <td class="py-5">Afternoon Time In</td>
                            <td class="py-5">Afternoon Time Out</td>
                        @else
                            <td class="py-5">Time In</td>
                            <td class="py-5">Time Out</td>
                        @endif

                        <td class="py-5">Date</td>
                    </tr>
                </thead>
                <tbody id="student_table_body">
                    @isset($students)
                        @foreach ($students as $student)
                            <tr>
                                <td class="py-2">{{ $student->s_fname . ' ' . $student->s_lname }} </td>
                                <td>{{ $student->s_program }}</td>
                                <td>{{ $student->s_set }}</td>
                                <td>{{ $student->s_lvl }}</td>


                                {{-- Morning Attendance --}}
                                <td>
                                    @if ($student->attend_checkIn)
                                        {{ date_format(date_create($student->attend_checkIn), 'h:i: A') }}
                                    @else
                                        <span class="text-red-500">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($student->attend_checkOut)
                                        {{ date_format(date_create($student->attend_checkOut), 'h:i: A') }}
                                    @else
                                        <span class="text-red-500">Absent</span>
                                    @endif
                                </td>
                                @if ($event->isWholeDay != 'false')
                                    {{-- Afternoon Attendance --}}
                                    <td>
                                        @if ($student->attend_afternoon_checkIn)
                                            {{ date_format(date_create($student->attend_afternoon_checkIn), 'h:i: A') }}
                                        @else
                                            <span class="text-red-500">Absent</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($student->attend_afternoon_checkOut)
                                            {{ date_format(date_create($student->attend_afternoon_checkOut), 'h:i: A') }}
                                        @else
                                            <span class="text-red-500">Absent</span>
                                        @endif
                                    </td>
                                @endif


                                <td class="text-wrap">{{ date_format(date_create($student->created_at), 'Y/m/d') }}
                                </td>
                            </tr>
                        @endforeach
                    @endisset

                </tbody>
            </table>
        </div>
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
