<?php
$page = 'students';
?>
<x-app-layout>
    @vite(['resources/js/students.js'])
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

    {{-- Session Error Handling from Import Controller --}}
    {{-- UPDATE: This error handling pop ups is not exclusive to the Import Controller anymore --}}
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

    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
            Students Masterlist
        </h2>
    </x-slot>
    <div class="flex justify-between items-center">
        <div class="flex flex-col items-start">
            <form action="{{ route('importStudent') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center">
                    <label for="file"
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                        Choose Excel File
                    </label>
                    <input type="file" name="file" id="file" class="hidden" onchange="selectFile(event)">
                    {{-- Choose Excel file and import it --}}
                    <button id="import-btn"
                        class="hidden focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        Import Data
                    </button>
                </div>
                <div class="preview mb-4 mt-2 p-1 border border-gray-300 rounded-lg bg-gray-100 shadow-md">
                    <p id="preview-name" class="text-gray-700 text-base font-semibold italic text-center">
                        No file currently selected
                    </p>
                </div>
            </form>
        </div>
    </div>
    {{-- Edit Student Information --}}
    <div x-data="{ open: false }" class="mt-4">
        <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div x-on:click.outside="open = false" class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
                <div class="border-b-2 border-green-500 mb-5">
                    <h1 class="text-2xl font-bold">
                        Edit Student Information
                    </h1>
                </div>
                <div class="mb-5">
                    <form id="updateForm" action="{{ route('updateStudent') }}" x-ref="updateForm" method="POST"
                        enctype="multipart/form-data" class="flex items-center overflow-y-scroll max-h-[400px]">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="id" id="s_ID" hidden>
                        <div class="basis-3/4 justify-start">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">
                                        RFID
                                    </label>
                                    <input type="text" placeholder="Scan RFID" name="s_rfid" id="s_RFID"
                                        value=""
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Student ID:</label>
                                    <input type="text" placeholder="Enter Student ID (Ex. 2023-00069)"
                                        name="s_studentID" id="s_STUDENTID"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">First Name:</label>
                                <input type="text" placeholder="Enter Firstname" name="s_fname" id="s_FNAME"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">Last Name:</label>
                                <input type="text" placeholder="Enter Lastname" name="s_lname" id="s_LNAME"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Middle Name</label>
                                    <input type="text" placeholder="Enter Middlename" name="s_mname" id="s_MNAME"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Suffix</label>
                                    <input type="text" placeholder="Enter Suffix" name="s_suffix" id="s_SUFFIX"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Program</label>
                                    <select name="s_program" id="s_PROGRAM"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Program</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSIS">BSIS</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Year Level</label>
                                    <select name="s_lvl" id="s_LVL"
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
                                    <select name="s_set" id="s_SET"
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
                        <div class="basis-1/4  mt-5 items-center gap-5">
                            <div x-data="{ image: '{{ asset('storage/private/sample_image.jpg') }}' }" class="flex flex-col items-center gap-5">
                                <img id="uploadImage" class="max-w-1/2" :src="image" alt="">
                                <input id="uploadFile" type="file" name="s_image" x-ref="imageFile"
                                    x-on:change="image = URL.createObjectURL($refs.imageFile.files[0])" hidden>
                                <button x-on:click="$refs.imageFile.click()" type="button"
                                    class="bg-green-400 text-white px-3 py-2 text-xl">
                                    Upload Image
                                </button>
                            </div>
                            <div>
                                <span>
                                    Change Student Status
                                </span>
                                <select name="s_status" id="s_STATUS"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="ENROLLED">ENROLLED</option>
                                    <option value="DROPPED">DROPPED</option>
                                    <option value="GRADUATED">GRADUATED</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="flex justify-end">
                    <button x-on:click="$refs.updateForm.submit()"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                    <button x-on:click="open = false"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
                </div>
            </div>
        </div>


        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex justify-between shadow-lg rounded-md border border-gray-400 my-2 p-2">
                <div class="">
                    <button id="selectAllBtn" onclick="selectAll()"
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                            </svg>

                            Select All
                        </div>
                    </button>
                </div>
                <div class="">
                    <button onclick="editSelectedRows()"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                            Edit
                        </div>
                    </button>
                    <button onclick="deleteSelectedRows()"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                            Delete
                        </div>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between py-1 w-full">

                <div class="flex gap-4">
                    <x-search :page="$page" :route="route('fetchStudentViaSearch')" />
                    {{-- FILTER --}}
                    <x-filter :page="$page" :route="route('fetchStudentsViaCategory')" />

                    <!-- Add Entries Per Page Filter -->
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
                </div>

                {{-- Add Student --}}
                <div class="z-50">
                    <x-new-modal>
                        <x-slot name="button">
                            <div class="flex px-1 py-3 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                                <span class="text-md">
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

            <div class="mt-6 bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Student Records
                        </h2>
                        <div class="flex items-center gap-3">
                            <span class="text-blue-100 text-sm font-medium">Total Students: {{ count($students) }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Student ID
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Personal Information
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                        </svg>
                                        Academic Details
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="student_table_body">
                            @isset($students)
                            @foreach ($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors duration-200" id="{{ $student->id }}">
                                <!-- Student ID Column -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $student->s_studentID }}
                                    </span>
                                </td>

                                <!-- Personal Information Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($student->s_fname, 0, 1) . substr($student->s_lname, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $student->s_fname . ' ' . $student->s_lname }}
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                @if($student->s_mname)
                                                <span class="text-gray-600">{{ $student->s_mname }}</span>
                                                @endif
                                                @if($student->s_suffix)
                                                <span class="text-gray-600">{{ $student->s_suffix }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Academic Details Column -->
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $student->s_program }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Set {{ $student->s_set }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Year {{ $student->s_lvl }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    @php
                                    $statusColors = [
                                    'ENROLLED' => 'bg-green-100 text-green-800',
                                    'GRADUATED' => 'bg-blue-100 text-blue-800',
                                    'DROPPED' => 'bg-red-100 text-red-800',
                                    'TO BE UPDATED' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $statusColor = $statusColors[$student->s_status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                        {{ $student->s_status }}
                                    </span>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <x-edit-button x-on:click="open = true"
                                            onclick="updateStudent({{ $student }}, '{{ asset('storage/' . $student['s_image']) }}')">
                                        </x-edit-button>
                                        <x-delete-button onclick="deleteStudent({{ $student }})">
                                        </x-delete-button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Enhanced Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">{{ count($students) }}</span> entries
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

            <!-- Add JavaScript for Enhanced Pagination and Filtering -->
            <script>
                let currentPage = 1;
                let entriesPerPage = 10;
                let filteredStudents = @json($students);
                let allStudents = @json($students);

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

                function filterStudents() {
                    const searchTerm = document.getElementById('searchStudents').value.toLowerCase();

                    filteredStudents = allStudents.filter(student =>
                        student.s_fname.toLowerCase().includes(searchTerm) ||
                        student.s_lname.toLowerCase().includes(searchTerm) ||
                        student.s_studentID.toLowerCase().includes(searchTerm) ||
                        student.s_rfid.toLowerCase().includes(searchTerm) ||
                        student.s_program.toLowerCase().includes(searchTerm)
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

                    students.forEach(student => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 transition-colors duration-200';
                        row.id = student.id;

                        row.innerHTML = `
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    ${student.s_studentID}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                ${student.s_fname.charAt(0).toUpperCase() + student.s_lname.charAt(0).toUpperCase()}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">
                                            ${student.s_fname} ${student.s_lname}
                                        </p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                            ${student.s_mname ? `<span class="text-gray-600">${student.s_mname}</span>` : ''}
                                            ${student.s_suffix ? `<span class="text-gray-600">${student.s_suffix}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                            ${student.s_program}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                            Set ${student.s_set}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                            Year ${student.s_lvl}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(student.s_status)}">
                                    ${student.s_status}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="updateStudent(${JSON.stringify(student).replace(/"/g, '&quot;')}, '${student.s_image ? 'storage/' + student.s_image : 'images/icons/default-image.svg'}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                        Edit
                                    </button>
                                    <button onclick="deleteStudent(${JSON.stringify(student).replace(/"/g, '&quot;')})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        `;

                        tbody.appendChild(row);
                    });
                }

                function getStatusColor(status) {
                    const statusColors = {
                        'ENROLLED': 'bg-green-100 text-green-800',
                        'GRADUATED': 'bg-blue-100 text-blue-800',
                        'DROPPED': 'bg-red-100 text-red-800',
                        'TO BE UPDATED': 'bg-yellow-100 text-yellow-800'
                    };
                    return statusColors[status] || 'bg-gray-100 text-gray-800';
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

    </div>

    <form action="{{ route('deleteStudent') }}" id="deleteStudent" method="POST" hidden>
        @csrf
        @method('DELETE')
        <input type="text" name="id" id="s_id" hidden>
    </form>

    {{-- MODALS FOR MULTIPLE EDIT AND DELETE --}}
    {{-- EDIT MODAL --}}
    <div id="multipleEditModal" class="inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
            <div class="border-b-2 border-gray-300 mb-5">
                <h1 class="text-2xl font-bold">
                    Edit Selected Students
                </h1>
            </div>
            <form class="mb-5" id="multiEditForm" action="{{ route('multiStudentEdit') }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" name="students" id="_selected_students_field" hidden>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Set:</label>
                    <select name="s_set" id=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $set)
                        <option value="{{ $set }}">{{ $set }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="my-3">
                    <label for="" class="font-semibold text-base">Status</label>
                    <select name="s_status" id=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['ENROLLED', 'GRADUATED', 'DROPPED', 'TO BE UPDATED'] as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Program</label>
                    <select name="s_program" id=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSIS">BSIS</option>
                    </select>
                </div>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Year Level</label>
                    <select name="s_lvl" id=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="flex justify-evenly">
                <button onclick="document.getElementById('multiEditForm').submit()"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-800 transition-colors">
                    Apply Changes
                </button>
                <button onclick="closeEditModal()"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>

    <form id="_selected_delete_form" method="POST" action="{{ route('multiStudentDelete') }}">
        @csrf
        @method('DELETE')
        <input type="text" name="students" id="_selected_students_delete" hidden>
    </form>

    <script>
        //FILE UPLOADED PREVIEW AND DISPLAYING OF IMPORT BUTTON
        function selectFile(event) {
            let preview = document.getElementById("preview-name");
            let importBtn = document.getElementById("import-btn");

            // Check if a file is selected
            if (event.target.files.length > 0) {
                const fileName = event.target.files[0].name; // Get file name
                preview.innerHTML = fileName; // Display file name
                importBtn.classList.remove('hidden'); //Show the import button
                console.log('File Upload: ' + fileName);
            }
        }

        // Accordion for Error Details
        function toggleAccordion() {
            let details = document.getElementById("errorDetails");
            details.classList.toggle("hidden");
        }
    </script>

</x-app-layout>