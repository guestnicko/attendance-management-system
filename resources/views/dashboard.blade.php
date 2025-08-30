<x-app-layout>
    @vite(['resources/js/app.js'])

    {{-- Add these styles at the top of the file --}}
    <style>
        .modal-backdrop {
            z-index: 60;
        }

        .modal-content {
            z-index: 61;
        }

        #tableContainer {
            z-index: 10;
            position: relative;
        }

        [x-cloak] {
            display: none !important;
        }

        .fixed {
            z-index: 50;
        }

        /* Ensure modals are hidden by default */
        .modal-hidden {
            display: none !important;
        }

        /* Prevent modal flashing on page load */
        [x-data] {
            opacity: 0;
            transition: opacity 0.1s ease-in-out;
        }

        [x-data].alpine-ready {
            opacity: 1;
        }
    </style>

    {{-- Add dashboard-specific JavaScript --}}
    <script>
        // Dashboard-specific functionality
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Dashboard DOM Content Loaded - Initializing Dashboard");

            // Initialize fullscreen toggle
            const fullscreenToggle = document.getElementById("fullscreenToggle");
            if (fullscreenToggle) {
                fullscreenToggle.addEventListener("click", function() {
                    let tableContainer = document.getElementById("tableContainer");
                    if (!document.fullscreenElement) {
                        tableContainer.requestFullscreen().catch((err) => {
                            console.error(
                                "Error attempting to enable full-screen mode:",
                                err
                            );
                        });
                    } else {
                        document.exitFullscreen();
                    }
                });
                console.log("Fullscreen toggle initialized");
            }

            // Initialize whole day event toggle
            const wholeDayCheckbox = document.querySelector("#wholeDay");
            const afternoonAttendance = document.querySelector("#afternoon_attendance");

            if (wholeDayCheckbox && afternoonAttendance) {
                wholeDayCheckbox.addEventListener("change", function() {
                    afternoonAttendance.classList.toggle("hidden");
                });
                console.log("Whole day toggle initialized");
            }

            // Initialize clock and date display
            const clockElement = document.getElementById("clock");
            const dateElement = document.getElementById("date");

            if (clockElement && dateElement) {
                // Wait for startTime function to be available
                const waitForStartTime = () => {
                    if (window.startTime) {
                        try {
                            window.startTime();
                            console.log("Clock initialized successfully");
                        } catch (error) {
                            console.error("Error initializing clock:", error);
                        }
                    } else {
                        // Wait a bit more and try again
                        setTimeout(waitForStartTime, 100);
                    }
                };

                // Start waiting for the function
                waitForStartTime();
            }
        });
    </script>

    {{-- Implemented Sweet Alert Pop Ups on Conditionals --}}
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
    {{-- Code by Panzerweb: Added second error handling for sweet alert --}}
    {{-- Error popup modified by Panzerweb --}}
    @if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const errors = @json(session('error'));
            console.log(errors);
            let errorList = '<ul class="pl-5 text-sm text-red-700">';
            for (const [key, value] of Object.entries(errors.details)) {
                errorList += `<li><strong>${key}:</strong> ${value}</li>`;
            }
            errorList += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                html: `   <h2 class="text-lg font-semibold text-red-600">Something is wrong!</h2><br>
                    <div class="w-full max-w-md mx-auto">
                        <div class="">
                            <button onclick="toggleAccordion()" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                View Details
                            </button>
                            <div id="errorDetails" class="hidden p-4 bg-red-100 border-t border-gray-300 rounded-lg">
                                    <div>
                                        <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0 9 9 0 0112.728 0zM12 8v4m0 4h.01" />
                                            </svg>
                                            Full Error Details
                                        </h3>
                                        <p class="text-sm text-red-600">
                                            The following information might help you identify and fix the issue:
                                        </p>
                                    </div>
                                    <div class="bg-gray-200 p-4 rounded-md border border-red-200">
                                        <p class="text-md font-medium text-red-800 mb-2">Error Message:</p>
                                        <p class="text-sm text-red-700 italic">
                                            The error shows that either a <b>Student RFID</b> or <b>Student ID</b> has been duplicated, or there are <b> empty fields </b>, please check carefully input details of inserted data.
                                        </p>
                                        <div class="bg-gray-100 p-4 rounded-md border border-red-200">
                                            <p class="text-sm font-medium text-red-800 mb-2">Details affected:</p>
                                            ${errorList}
                                        </div>

                                        <span class="text-sm"><strong>Full error message: </strong>${errors.message}</span>
                                    </div>

                            </div>
                        </div>
                    </div>`,
                showConfirmButton: true,
            });
        });
    </script>
    @endif


    <div class="flex gap-5 mb-4">
        <div class="bg-gray-900 h-auto basis-1/2 p-3 rounded-md flex flex-col justify-between">
            <div class="flex justify-between">
                <div class="py-1 px-3">
                    <h2 class="text-4xl font-semibold text-green-500">Welcome, <span
                            class="text-gray-200">{{ ucwords(auth()->user()->admin_uname) }}</span></h2>
                    <p class="text-gray-200 pt-1">RFID Attendance Management System</p>
                </div>
                <div class="py-1 px-3">
                    <a href="{{ route('profile.edit') }}">
                        <button type="button"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </a>
                </div>
            </div>
            <div class="flex justify-start gap-3">
                <button onclick="location.href = '{{ route('attendance') }}'"
                    class="bg-gradient-to-r from-lime-500 to-lime-600 hover:bg-green-700 hover:scale-105 ease-linear transition-all text-white rounded-2xl px-4 py-4 text-lg flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                    </svg>
                    Attendance

                </button>
            </div>
        </div>
        <div class="bg-gray-900 basis-1/2 rounded-lg p-4 shadow-md">
            <div class="flex flex-col items-start">
                <h1 class="text-4xl font-semibold text-gray-200">Student Statistics</h1>
            </div>
            <div class="flex gap-5 justify-center items-center py-10">
                <!-- Students Card -->
                <div onclick="window.location.href = '{{ route('students') }}'"
                    class="flex items-center gap-3 bg-gradient-to-r from-lime-600 to-lime-700 text-white rounded-lg p-4 shadow-lg hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-14 h-14">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ $studentCount }}</h1>
                        <p class="text-lg font-medium opacity-90">Enrolled Students</p>
                    </div>
                </div>

                <!-- Graduates Card -->
                <div onclick="window.location.href = '{{ route('students') }}'"
                    class="flex items-center gap-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-4 shadow-lg hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-14 h-14">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ $graduateCount }}</h1>
                        <p class="text-lg font-medium opacity-90">Graduates</p>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="flex items-center justify-between bg-white p-3 rounded-lg">
        <div class="flex gap-5">
            <div id="clockdate">
                <div
                    class="clockdate-wrapper bg-gray-800 p-2 max-w-xs w-full text-center rounded-xl mx-auto shadow-lg border-2 border-gray-900">
                    <div id="clock" class="bg-gray-800 text-lime-500 text-2xl font-sans shadow-sm"></div>
                    <div id="date" class="tracking-widest text-sm font-sans text-white"></div>
                </div>
            </div>
        </div>

        {{-- Update the modals wrapper to include higher z-index --}}
        <div class="flex gap-3 relative z-50">
            {{-- MODALS --}}
            <x-new-modal>
                <x-slot name="button"
                    class="bg-green-500 hover:bg-green-600 ease-linear transition-all text-white rounded-xl px-5 text-2xl
                    flex items-center p-4 gap-1">
                    <div class="flex px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        Event
                    </div>
                </x-slot>

                <x-slot name="heading">
                    Create Event
                </x-slot>
                <x-slot name="content">
                    <form x-ref="eventForm" action="{{ route('addEvent') }}" method="POST" class="min-w-[500px]">
                        @csrf

                        <div class="flex flex-col mb-3">
                            <label for="">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl text-gray-900 font-bold">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="start-time" name="checkIn_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="checkIn_end" name="checkIn_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <p class="text-xl text-gray-900 font-bold">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="start-time" name="checkOut_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="end-time" name="checkOut_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="wholeDay" name="wholeDay">
                            <label for="">Whole Day?</label>
                        </div>

                        {{-- AFTERNOON ATTENDANCE --}}

                        <div id="afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-bold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="start-time" name="afternoon_checkIn_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="end-time" name="afternoon_checkIn_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                            <p class="text-xl text-gray-900 font-bold">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="start-time" name="afternoon_checkOut_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="end-time" name="afternoon_checkOut_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </x-slot>
                <x-slot name="footer">
                    <button x-on:click="$refs.eventForm.submit()" type="submit"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                </x-slot>
            </x-new-modal>
            {{-- MODALS --}}
            <x-new-modal>
                <x-slot name="button">
                    <div class="flex px-3 py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-7 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        Student
                </x-slot>


                <x-slot name="heading">
                    Add Student Information
                </x-slot>
                <x-slot name="content">
                    <form id="studentForm" action="{{ route('addStudent') }}" x-ref="studentForm" method="POST"
                        enctype="multipart/form-data" class="flex items-center">
                        @csrf
                        <div class="basis-3/4 justify-start">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">
                                        RFID
                                    </label>
                                    <input type="text" placeholder="Scan RFID" name="s_rfid" id="s_rfid"
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
                                <input type="text" placeholder="Enter Firstname" name="s_fname" id="s_fname"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">Last Name:</label>
                                <input type="text" placeholder="Enter Lastname" name="s_lname" id="s_lname"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Middle Name</label>
                                    <input type="text" placeholder="Enter Middlename" name="s_mname"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        id="s_mname">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Suffix</label>
                                    <input type="text" placeholder="Enter Suffix" name="s_suffix" id="s_suffix"
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
                            <img id="uploadImage" class="max-w-1/2 w-100" :src="image" alt="">
                            <input id="uploadFile" type="file" name="s_image" x-ref="imageFile"
                                x-on:change="image = URL.createObjectURL($refs.imageFile.files[0])" hidden>
                            <button x-on:click="$refs.imageFile.click()" type="button"
                                class="bg-green-600 rounded-xl text-white px-3 py-2 text-xl">
                                Upload Image
                            </button>
                        </div>
                    </form>
                </x-slot>
                <x-slot name="footer">
                    <button onclick="testStudentForm()" class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Test Form </button>
                    <button x-on:click="$refs.studentForm.submit()"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                </x-slot>
            </x-new-modal>

        </div>
    </div>


    <div class="mt-6 bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                    Attendance Records
                </h2>
                <div class="flex items-center gap-3">
                    <span class="text-green-100 text-sm font-medium">Total Students: {{ $studentCount }}</span>
                    <button id="fullscreenToggle" class="bg-green-500 hover:bg-green-400 text-white p-2 rounded-lg transition-colors duration-200" title="Toggle Fullscreen">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                        </svg>
                    </button>
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
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                            <div class="flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Afternoon Session
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                Event Details
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($attendances as $attendance)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <!-- Student Information Column -->
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr($attendance->s_fname, 0, 1) . substr($attendance->s_lname, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $attendance->s_fname . ' ' . $attendance->s_lname }}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                            {{ $attendance->s_program }}
                                        </span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
                                            Set {{ $attendance->s_set }}
                                        </span>
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full font-medium">
                                            Year {{ $attendance->s_lvl }}
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
                                    @if ($attendance->attend_checkIn)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ date('h:i A', strtotime($attendance->attend_checkIn)) }}
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
                                    @if ($attendance->attend_checkOut)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ date('h:i A', strtotime($attendance->attend_checkOut)) }}
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

                        <!-- Afternoon Session Column -->
                        <td class="px-6 py-4 text-center border-r border-gray-200">
                            <div class="space-y-2">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Check In:</span>
                                    @if ($attendance->attend_afternoon_checkIn)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ date('h:i A', strtotime($attendance->attend_afternoon_checkIn)) }}
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
                                    @if ($attendance->attend_afternoon_checkOut)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ date('h:i A', strtotime($attendance->attend_afternoon_checkOut)) }}
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

                        <!-- Event Details Column -->
                        <td class="px-6 py-4 text-center">
                            <div class="space-y-2">
                                <div class="flex items-center justify-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        {{ $attendance->event_name }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ date('M d, Y', strtotime($attendance->date)) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900">No attendance records found</h3>
                                <p class="text-sm text-gray-500">Start tracking attendance by creating an event and recording student check-ins.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">{{ $studentCount }}</span> entries
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

    <!-- Add JavaScript for Dashboard Pagination and Filtering -->
    <script>
        // Simple pagination and search functionality
        let currentPage = 1;
        let entriesPerPage = 10;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard initialized');
        });

        function changeEntriesPerPage(value) {
            entriesPerPage = parseInt(value);
            // Reload the page with new entries per page
            window.location.href = window.location.pathname + '?entries=' + value;
        }

        function filterAttendance() {
            const searchTerm = document.getElementById('searchAttendance').value.toLowerCase();
            // Simple client-side filtering for existing data
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Simple pagination functions
        function previousPage() {
            // For now, just reload the page
            window.location.reload();
        }

        function nextPage() {
            // For now, just reload the page
            window.location.reload();
        }
    </script>

    <script>
        // Code by Panzerweb: for error details accordion
        // Accordion for Error Details
        function toggleAccordion() {
            let details = document.getElementById("errorDetails");
            details.classList.toggle("hidden");
        }
    </script>

</x-app-layout>

{{-- Removed the script file, transferred them into dashboard.js --}}