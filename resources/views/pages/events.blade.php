<x-app-layout>

    @vite(['resources/js/events.js'])
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
                    text: '{{ session('
                                                                                                                                                            success ') }}',
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endif


    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
                Events Dashboard
            </h2>
        </div>
    </x-slot>
    <div class="mt-4" x-data="{ open: false }">
        {{-- EDIT EVENT MODAL --}}
        <div x-show="open" x-cloak id="udpateEventModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div x-on:click.outside="open = false" class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
                <div class="border-b-2 border-gray-300 mb-5">
                    <h1 class="text-2xl font-bold">
                        Edit Event
                    </h1>
                </div>
                <div class="mb-5 max-h-[400px] overflow-y-scroll">
                    <form x-ref="updateForm" action="{{ route('updateEvent') }}" method="POST" class="min-w-[500px]">
                        @csrf
                        @method('PATCH')
                        <input type="text" id="evn_id" name="id" hidden>
                        <div class="flex flex-col mb-3">
                            <label for="">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name" id="evn_name">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="" class="mb-2">Event Fines:</label>
                            <input type="number" placeholder="Enter Event Fine" name="fines_amount" id="evn_fines"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date" id="evn_date"
                                class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl font-semibold text-gray-900">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" name="checkIn_start" id="in_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" name="checkIn_end" id="in_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" name="checkOut_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" id="out_start" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                    <input type="time" name="checkOut_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" id="out_end" required />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="isWholeDay" name="wholeDay"
                                onchange="handleWholeDayChange(this)">
                            <label for="">Whole Day?</label>
                        </div>
                        <div id="afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-semibold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        <input type="time" id="afternoon_in_start" name="afternoon_checkIn_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                        <input type="time" id="afternoon_in_end" name="afternoon_checkIn_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                            <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        <input type="time" id="afternoon_out_start"
                                            name="afternoon_checkOut_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                        <input type="time" id="afternoon_out_end" name="afternoon_checkOut_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="flex justify-end">
                    <button x-on:click="$refs.updateForm.submit()" type="submit"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                    <button x-on:click="open = false"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 z-10">Close</button>
                </div>
            </div>
        </div>


        {{-- CREATE EVENT MODAL --}}
        <div class="flex justify-between items-center mb-3">

            <x-new-modal>
                <x-slot name="button"
                    class="bg-green-600 hover:bg-green-950 ease-linear transition-all duration-75 text-white rounded-xl px-2 text-[10px] flex items-center p-2 gap-1 w-5">
                    <div class="flex px-2 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5 mt-1 m-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
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
                            <label for="" class="mb-2">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="" class="mb-2">Event Fines:</label>
                            <input type="number" placeholder="Enter Event Fine" name="fines_amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date"
                                class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl font-semibold text-gray-900">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                        <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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

                            {{-- AFTERNOON ATTENDANCE --}}

                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="wholeDay" name="wholeDay"
                                onchange="handleCreateWholeDayChange(this)">
                            <label for="">Whole Day?</label>
                        </div>
                        <div id="create_afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-semibold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                            <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                        class="bg-green-400 text-white px-3 py-2 mx-4 hover:bg-green-600 rounded-md">
                        Save </button>

                </x-slot>
            </x-new-modal>
        </div>


        {{-- Event Buttons --}}
        <div class="w-full mt-6">
            <div class="inline-flex rounded-md shadow-xs" role="group">
                <button class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition rounded-tl-md"
                    onclick="navigateTab('pendingEventTable', this.id)" id="pendingEventButton">Pending
                    Events
                </button>
                <button class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition"
                    onclick="navigateTab('completedEventTable', this.id)" id="completedEventButton">Completed
                    Events
                </button>

                <button class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition rounded-tr-md"
                    onclick="navigateTab('deletedEventTable', this.id)" id="deletedEventButton">
                    Deleted Events
                </button>
            </div>

            <!-- Test Modal Button -->
            <!-- <div class="mt-4">
                <button onclick="testModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Test Modal (Debug)
                </button>
            </div> -->

            <!-- Add Filtering Controls -->
            <div class="mt-4 flex flex-wrap items-center gap-4">
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
                    <label for="searchEvents" class="text-sm font-medium text-gray-700">Search:</label>
                    <input type="text" id="searchEvents" placeholder="Search events..." onkeyup="filterEvents()"
                        class="border border-gray-300 rounded-md px-3 py-1 text-sm w-48">
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        Events Management
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-green-100 text-sm font-medium">Total Events:
                            {{ count($pendingEvents) + count($completedEvents) + count($deletedEvents) }}</span>
                        <span id="currentTabStatus" class="text-green-100 text-sm font-medium">Pending:
                            {{ count($pendingEvents) }}</span>
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
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Event Details
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Time Schedule
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Afternoon Schedule
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
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="events_table_body" class="bg-white divide-y divide-gray-200">
                        <!-- Events will be populated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span
                    id="totalEntries">{{ count($pendingEvents) + count($completedEvents) + count($deletedEvents) }}</span>
                entries
            </div>
            <div class="flex items-center gap-2">
                <button onclick="previousPage()" id="prevBtn"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <div id="pageNumbers" class="flex items-center gap-1">
                    <!-- Page numbers will be generated here -->
                </div>
                <button onclick="nextPage()" id="nextBtn"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>

        <!-- Add JavaScript for Pagination and Filtering -->
        <script>
            let currentPage = 1;
            let entriesPerPage = 10;
            let filteredEvents = [];
            let currentTab = 'pendingEventTable';

            // Store events data from PHP
            const pendingEvents = @json($pendingEvents ?? []);
            const completedEvents = @json($completedEvents ?? []);
            const deletedEvents = @json($deletedEvents ?? []);

            // Debug: Log the events data
            console.log('Events data loaded:');
            console.log('Pending events:', pendingEvents);
            console.log('Completed events:', completedEvents);
            console.log('Deleted events:', deletedEvents);

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM Content Loaded - Initializing events page');

                // Check if Alpine.js is available
                if (typeof Alpine !== 'undefined') {
                    console.log('Alpine.js is available');
                } else {
                    console.log('Alpine.js is not available');
                }

                // Check modal container
                const modalContainer = document.querySelector('[x-data]');
                console.log('Modal container found:', modalContainer);
                if (modalContainer && modalContainer.__x) {
                    console.log('Alpine.js data available:', modalContainer.__x.$data);
                }

                initializeTable();
                updatePagination();
                updateTabStatus();

                // Initialize event handlers for whole day checkboxes - Now handled by JavaScript functions
                // Functions are available globally: handleWholeDayChange() and handleCreateWholeDayChange()
            });

            function initializeTable() {
                console.log('Initializing table with pending events:', pendingEvents);
                filteredEvents = pendingEvents;
                displayEvents();
            }

            function changeEntriesPerPage(value) {
                entriesPerPage = parseInt(value);
                currentPage = 1;
                updatePagination();
                displayEvents();
            }

            function filterEvents() {
                const searchTerm = document.getElementById('searchEvents').value.toLowerCase();

                let allEvents = [];
                if (currentTab === 'pendingEventTable') {
                    allEvents = pendingEvents;
                    console.log('Filtering pending events:', pendingEvents);
                } else if (currentTab === 'completedEventTable') {
                    allEvents = completedEvents;
                    console.log('Filtering completed events:', completedEvents);
                } else if (currentTab === 'deletedEventTable') {
                    allEvents = deletedEvents;
                    console.log('Filtering deleted events:', deletedEvents);
                }

                // Apply search filter if there's a search term
                if (searchTerm) {
                    filteredEvents = allEvents.filter(event =>
                        event.event_name.toLowerCase().includes(searchTerm) ||
                        event.date.includes(searchTerm)
                    );
                } else {
                    filteredEvents = allEvents;
                }

                console.log('Current tab:', currentTab, 'Filtered events:', filteredEvents);

                currentPage = 1;
                updatePagination();
                displayEvents();
            }

            function displayEvents() {
                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = startIndex + entriesPerPage;
                const eventsToShow = filteredEvents.slice(startIndex, endIndex);

                // Update table body based on current tab
                updateTableBody(eventsToShow);

                // Update entry counts
                document.getElementById('startEntry').textContent = startIndex + 1;
                document.getElementById('endEntry').textContent = Math.min(endIndex, filteredEvents.length);
                document.getElementById('totalEntries').textContent = filteredEvents.length;
            }

            function updateTableBody(events) {
                const tbody = document.getElementById('events_table_body');
                if (!tbody) return;

                tbody.innerHTML = '';

                if (events.length === 0) {
                    let emptyMessage = '';
                    let emptyDescription = '';

                    if (currentTab === 'pendingEventTable') {
                        emptyMessage = 'No pending events';
                        emptyDescription = 'All events have been processed or there are no events created yet.';
                    } else if (currentTab === 'completedEventTable') {
                        emptyMessage = 'No completed events';
                        emptyDescription = 'No events have been marked as completed yet. Complete an event to see it here.';
                    } else if (currentTab === 'deletedEventTable') {
                        emptyMessage = 'No deleted events';
                        emptyDescription = 'No events have been deleted yet.';
                    } else {
                        emptyMessage = 'No events found';
                        emptyDescription = 'No events match your search criteria.';
                    }

                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900">${emptyMessage}</h3>
                                    <p class="text-sm text-gray-500">${emptyDescription}</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                events.forEach(event => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition-colors duration-200';

                    row.innerHTML = `
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                ${event.event_name.charAt(0).toUpperCase()}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            ${event.event_name}
                                        </p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                                ${event.date}
                                            </span>
                                            ${event.fines_amount ? `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full font-medium">
                                                                                                        ₱${event.fines_amount}
                                                                                                    </span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center border-r border-gray-200">
                            <div class="space-y-2">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Check In:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ${event.checkIn_start} - ${event.checkIn_end}
                                    </span>
                                </div>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${event.checkOut_start} - ${event.checkOut_end}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center border-r border-gray-200">
                            <div class="space-y-2">
                                ${event.isWholeDay === 'true' ? `
                                                                                            <div class="flex items-center justify-center gap-2">
                                                                                                <span class="text-xs text-gray-500 font-medium">Check In:</span>
                                                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                                                    ${event.afternoon_checkIn_start || '00:00'} - ${event.afternoon_checkIn_end || '00:00'}
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="flex items-center justify-center gap-2">
                                                                                                <span class="text-xs text-gray-500 font-medium">Check Out:</span>
                                                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                                                    ${event.afternoon_checkOut_start || '00:00'} - ${event.afternoon_checkOut_end || '00:00'}
                                                                                                </span>
                                                                                            </div>
                                                                                        ` : `
                                                                                            <span class="text-xs text-gray-500">Half-day event</span>
                                                                                        `}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editEvent(${JSON.stringify(event).replace(/"/g, '&quot;')})"
                                    class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200">
                                    Edit
                                </button>
                                <button onclick="deleteEvent(${JSON.stringify(event).replace(/"/g, '&quot;')})"
                                    class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
                                    Delete
                                </button>
                            </div>
                        </td>
                    `;

                    tbody.appendChild(row);
                });
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredEvents.length / entriesPerPage);
                const pageNumbers = document.getElementById('pageNumbers');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');

                if (!pageNumbers || !prevBtn || !nextBtn) return;

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
                displayEvents();
                updatePagination();
            }

            function previousPage() {
                if (currentPage > 1) {
                    currentPage--;
                    displayEvents();
                    updatePagination();
                }
            }

            function nextPage() {
                const totalPages = Math.ceil(filteredEvents.length / entriesPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    displayEvents();
                    updatePagination();
                }
            }

            function navigateTab(tableId, buttonId) {
                console.log('Navigating to tab:', tableId, 'Button:', buttonId);

                // Update current tab
                currentTab = tableId;
                console.log('Current tab set to:', currentTab);

                // Update button styles
                document.querySelectorAll('[id$="Button"]').forEach(btn => {
                    btn.className = 'px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition';
                });
                document.getElementById(buttonId).className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white transition';

                // Update filtered events based on tab
                console.log('Calling filterEvents() for tab:', currentTab);
                filterEvents();

                // Update the status display
                updateTabStatus();
            }

            function updateTabStatus() {
                const statusElement = document.getElementById('currentTabStatus');
                if (!statusElement) return;

                let statusText = '';
                let eventCount = 0;

                if (currentTab === 'pendingEventTable') {
                    statusText = `Pending: ${pendingEvents.length}`;
                    eventCount = pendingEvents.length;
                } else if (currentTab === 'completedEventTable') {
                    statusText = `Completed: ${completedEvents.length}`;
                    eventCount = completedEvents.length;
                } else if (currentTab === 'deletedEventTable') {
                    statusText = `Deleted: ${deletedEvents.length}`;
                    eventCount = deletedEvents.length;
                }

                statusElement.textContent = statusText;

                // Update the total count display as well
                const totalElement = document.querySelector('.text-green-100.text-sm.font-medium');
                if (totalElement && totalElement.textContent.includes('Total Events:')) {
                    totalElement.textContent = `Total Events: ${eventCount}`;
                }
            }

            // Edit Event Function - Moved to events.js file
            // Function is now available globally as editEvent()

            // Initialize event handlers for whole day checkboxes - Now handled by JavaScript functions
            // Functions are available globally: handleWholeDayChange() and handleCreateWholeDayChange()

            // Test Modal Function for debugging
            function testModal() {
                console.log('=== Testing Modal ===');
                console.log('Alpine.js available:', typeof Alpine !== 'undefined');

                // Check for modal elements
                const modalContainer = document.querySelector('[x-data="globalModal"]');
                console.log('Modal container with x-data="globalModal":', modalContainer);

                const modalOverlay = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
                console.log('Modal overlay:', modalOverlay);

                // Try to open modal using the same approach as editEvent
                let modalOpened = false;

                // Approach 1: Access globalModal component via Alpine.js
                if (typeof Alpine !== 'undefined') {
                    try {
                        const globalModal = Alpine.data('globalModal');
                        console.log('globalModal component:', globalModal);
                        if (globalModal) {
                            if (typeof globalModal === 'function') {
                                const modalInstance = globalModal();
                                console.log('globalModal instance:', modalInstance);
                                if (modalInstance && modalInstance.open !== undefined) {
                                    modalInstance.open = true;
                                    modalOpened = true;
                                    console.log('Modal opened via globalModal component');
                                }
                            }
                        }
                    } catch (e) {
                        console.log('globalModal component access failed:', e);
                    }
                }

                // Approach 2: Try to find Alpine.js instance on the container
                if (!modalOpened && modalContainer && modalContainer.__x) {
                    try {
                        console.log('Alpine.js data:', modalContainer.__x.$data);
                        modalContainer.__x.$data.open = true;
                        modalOpened = true;
                        console.log('Modal opened via Alpine.js __x data');
                    } catch (e) {
                        console.log('Alpine.js __x access failed:', e);
                    }
                }

                // Approach 3: Direct DOM manipulation as fallback
                if (!modalOpened) {
                    console.log('Alpine.js not accessible, trying direct DOM');
                    if (modalOverlay) {
                        modalOverlay.style.display = 'flex';
                        modalOverlay.classList.remove('hidden');
                        modalOpened = true;
                        console.log('Modal opened via direct DOM');
                    }
                }

                if (!modalOpened) {
                    console.error('All modal opening approaches failed');
                }
            }
        </script>

    </div>

    {{-- MODAL FOR EXPORTING EVENTS  --}}

    <form method="POST" id="deleteEvent" action="{{ route('deleteEvent') }}" hidden>
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <input type="text" name="id" id="delete_event_id" hidden>
    </form>
</x-app-layout>
