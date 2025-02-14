<x-app-layout>
    @vite(['resources/js/axios.js', 'resources/js/student_attendance.js'])

    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-violet-800 leading-tight">
            {{ __('Student Attendance') }}
        </h2>
        <div class="flex justify-between items-start p-4 bg-gray-100 border-b">
            <div>
                <h2 class="text-4xl font-bold text-gray-800">
                    Today's Event:

                    @if ($event)
                        <span class="text-gray-500 italic">
                            {{ $event->event_name }}
                        </span>
                    @else
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                title: "Oops!",
                                text: "There are no events yet!",
                                icon: "warning"
                                });
                            });
                        </script>
                        <span class="text-red-600">
                            There are no event details yet
                        </span>
                    @endif

                </h2>

                @if ($event)
                    <div class="flex justify-between gap-3 py-1">
                        <div class="block">
                            <h2 class="text-lg font-bold text-gray-800">
                                Check In Start:
                                <span class="text-gray-500">
                                    {{ date_format(date_create($event->checkIn_start), 'h:i A') }}
                                </span>
                            </h2>
                            <h2 class="text-lg font-bold text-gray-800">
                                Check In End:
                                <span class="text-gray-500">
                                    {{ date_format(date_create($event->checkIn_end), 'h:i A') }}
                                </span>
                            </h2>
                        </div>
                        <div class="block">
                            <h2 class="text-lg font-bold text-gray-800">
                                Check Out Start:
                                <span class="text-gray-500">
                                    {{ date_format(date_create($event->checkOut_start), 'h:i A') }}
                                </span>
                            </h2>
                            <h2 class="text-lg font-bold text-gray-800">
                                Check Out Start:
                                <span class="text-gray-500">
                                    {{ date_format(date_create($event->checkOut_end), 'h:i A') }}
                                </span>
                            </h2>
                        </div>

                    </div>
                    <h2 class="text-sm font-bold text-gray-800 py-3">
                        Date Created:
                        <span class="text-gray-500">
                            {{ date_format(date_create($event->date), 'Y-m-d, h:i A') }}
                        </span>
                    </h2>
                @endif


            </div>

            @if ($event)
                <div x-data="{ play: false }" class="flex">
                    <div x-data="{ open: false }" class="transition-all">
                        <div class="flex-col justify-end">
                            <button x-on:click="open = ! open" onclick="myFunction()"
                            class="bg-orange-500 px-3 py-2 mb-2 hover:bg-orange-600 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                            Enter Student ID:
                        </button>
                        <button onclick="startInterval()"
                            class="bg-orange-500 px-3 py-2 mb-2 hover:bg-orange-600 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                            Start Attendance
                        </button>
                        </div>

                        <div x-show.important="open"
                            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                            <div id="modalAttendance" x-on:click.outside="open = false"
                                class="max-w-[1000px] min-w-[500px] bg-white p-6 rounded-lg shadow-lg">
                                <div class="border-b-2 border-gray-300 mb-5">
                                    <h1 class="text-2xl font-bold">
                                        Attendance is Starting
                                    </h1>
                                </div>
                                <div class="mb-5">
                                    <form id="attendanceForm" method="POST" action="{{route('attendanceStudent')}}">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="uri" value="{{ route('attendanceStudent') }}">
                                        <div class="flex flex-col">
                                            <label class="text-lg font-semibold" for="">Enter RFID:</label>
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
        <div class="flex justify-between">

            <h3 class="text-3xl text-violet-800 font-extrabold">
                Attendance Record
            </h3>
        </div>

        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold">
                <thead class="text-base text-gray-950 uppercase bg-gray-50">
                    <tr class="bg-violet-200 text-violet-900 py-2 text-lg font-semibold">
                        <td>Name</td>
                        <td>Program</td>
                        <td>Set</td>
                        <td>Year Level</td>
                        <td>Time In</td>
                        <td>Time Out</td>
                        <td>Date</td>
                    </tr>
                </thead>
                <tbody id="student_table_body">
                    @isset($students)
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->s_fname . " ". $student->s_lname }} </td>
                                <td>{{$student->s_program}}</td>
                                <td>{{$student->s_set}}</td>
                                <td>{{$student->s_lvl}}</td>
                                <td>{{$student->attend_checkIn}}</td>
                                <td>{{$student->attend_checkOut}}</td>
                                <td>{{$student->created_at}}</td>
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
        <input type="text" name="s_rfid" id="inputField1" class="bg-transparent border-none" autocomplete="off">
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

        Swal.fire({
            icon: "info",
            title: "Attendance is now starting!",
            showConfirmButton: false,
            timer: 1000
        });

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
