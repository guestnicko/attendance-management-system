// Axios is already available globally through axios.js
// No need to import it here

function updateStudent(data, img) {
    document.getElementById("s_RFID").value = data.s_rfid;
    document.getElementById("s_STUDENTID").value = data.s_studentID;
    document.getElementById("s_MNAME").value = data.s_mname;
    document.getElementById("s_FNAME").value = data.s_fname;
    document.getElementById("s_LNAME").value = data.s_lname;
    document.getElementById("s_SUFFIX").value = data.s_suffix;
    document.getElementById("s_PROGRAM").value = data.s_program;
    document.getElementById("s_STATUS").value = data.s_status;
    document.getElementById("s_LVL").value = data.s_lvl;
    document.getElementById("s_SET").value = data.s_set;
    document.getElementById("s_ID").value = data.id;
    document.getElementById("uploadImage").src = img;
}
document.updateStudent = updateStudent;

function deleteStudent(data) {
    Swal.fire({
        title: "Do you really want to delete this student?",
        html: `
            <strong>There is no reverting this!</strong>
`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("s_id").value = data.id;
            document.getElementById("deleteStudent").submit();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "User Deleted Successfully",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
}
const table_row = document.getElementsByClassName("table_row");

document.deleteStudent = deleteStudent;
Array.from(table_row).forEach((element) => {
    element.addEventListener("click", (e) => {
        // element.classList.toggle('selected', 'bg-green-500', 'shadow-lg', 'shadow-green-800')
        element.classList.toggle("selected");
        element.classList.toggle("bg-green-400");
    });
});

document.addEventListener("dblclick", (e) => {
    const selected = document.querySelectorAll(".selected");
    Array.from(selected).forEach((element) => {
        element.classList.remove("bg-green-400", "selected");
    });
});

// MULTIPLE EDIT AND DELETE AND SELECT

window.closeEditModal = closeEditModal;

function closeEditModal() {
    document.querySelector("#multipleEditModal").classList.add("hidden");
    document.querySelector("#multipleEditModal").classList.remove("flex");
}

window.selectAll = selectAll;
function selectAll() {
    const rows = Array.from(document.querySelectorAll(".table_row"));
    const selected_count = Array.from(
        document.querySelectorAll(".selected")
    ).length;

    if (selected_count != rows.length) {
        rows.forEach((e) => {
            e.classList.add("selected", "bg-green-400");
        });
        document.querySelector("#selectAllBtn").innerHTML = `
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                </svg>

                Deselect All
            </div>
            `;
        return;
    }
    rows.forEach((e) => {
        e.classList.remove("selected", "bg-green-400");
    });
    document.querySelector("#selectAllBtn").innerHTML = `
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                </svg>

                Select All
            </div>
            `;
}

window.editSelectedRows = editSelectedRows;
function editSelectedRows() {
    const selected = Array.from(document.querySelectorAll(".selected"));

    // DISPLAY MODAL
    document.querySelector("#multipleEditModal").classList.remove("hidden");
    document.querySelector("#multipleEditModal").classList.add("fixed");
    document.querySelector("#multipleEditModal").classList.add("flex"); //Added Flex

    // UPDATE MODAL
    const field = document.querySelector("#_selected_students_field");
    field.value = selected.map((cb) => cb.id);
}

window.deleteSelectedRows = deleteSelectedRows;
async function deleteSelectedRows() {
    // Added a confirmation Sweet Alert Pop up before Deleting
    Swal.fire({
        title: "Chotto Matte Kudasai!!!",
        html: `
    <strong>Are you sure to delete this student's data?</strong>
    `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            const field = document.querySelector("#_selected_students_delete");
            const selected = Array.from(
                document.querySelectorAll(".selected")
            ).map((e) => e.id);

            field.value = selected;

            document.querySelector("#_selected_delete_form").submit();
        }
    });
}

// AXIOS API

// FOR MULTIPLE EDITS
const form = document.getElementById("multiEditForm");

function multiEdit() {
    return false;
}

window.renderTable = renderTable;
function renderTable(students) {
    const table = document.getElementById("student_table_body");
    const table_row = document.getElementsByClassName("table_row");
    table.innerHTML = "";
    const statusColors = {
        ENROLLED: "bg-green-100 text-green-800",
        GRADUATED: "bg-blue-100 text-blue-800",
        DROPPED: "bg-red-100 text-red-800",
        "TO BE UPDATED": "bg-yellow-100 text-yellow-800",
    };

    if (students) {
        students.forEach((student) => {
            const statusColor =
                statusColors[student.s_status] || "bg-gray-100 text-gray-800";

            table.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors duration-200" id="{{ $student->id }}">
                <td class="px-6 py-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    ${student.s_studentID}
                    </span>
                </td>

                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">
                                ${student.s_fname
                                    .charAt(0)
                                    .toUpperCase()}${student.s_lname
                .charAt(0)
                .toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">
                                ${student.s_fname} ${student.s_lname}
                            </p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                ${
                                    student.s_mname
                                        ? `<span class="text-gray-600">${student.s_mname}</span>`
                                        : ""
                                }
                                ${
                                    student.s_suffix
                                        ? `<span class="text-gray-600">${student.s_suffix}</span>`
                                        : ""
                                }
                            </div>
                        </div>
                    </div>
                </td>

                <!-- Academic Details Column -->
                <td class="px-6 py-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span
                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                ${student.s_program}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                Set ${student.s_set}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                Year ${student.s_lvl}
                            </span>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4">

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusColor}">
                        ${student.s_status}
                    </span>
                </td>

                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-3">
                        <x-edit-button x-on:click="open = true"
                            onclick="updateStudent(${JSON.stringify(
                                student
                            )}, '${student.s_image}')">
                        </x-edit-button>
                        <x-delete-button onclick="deleteStudent(${JSON.stringify(
                            student
                        )})">
                        </x-delete-button>
                    </div>
                </td>
            </tr>`;
            document.getElementById("std_info_table").style.display = "none"; //Line by Panzerweb: When search is empty, remove the span
        });
        Array.from(table_row).forEach((element) => {
            element.addEventListener("click", (e) => {
                // element.classList.toggle('selected', 'bg-green-500', 'shadow-lg', 'shadow-green-800')
                element.classList.toggle("selected");
                element.classList.toggle("bg-green-400");
            });
        });
    } else {
        //Code by Panzerweb: If search does not match, display text 'No Student Found'
        document.getElementById("std_info_table").style.display = "block";
        document.getElementById(
            "std_info_table"
        ).innerHTML = `<h3 class="text-center tracking-wide text-gray-500 text-xl">No Student Found</h3>`;
    }
}

// TEST PURPOSES
export function testStudentForm() {
    // Added Randomality of Test Users
    const testUsers = [
        {
            s_rfid: "0002803473",
            s_studentID: "2023-00364",
            s_fname: "Romeo Selwyn",
            s_mname: "Molejon",
            s_lname: "Villar",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "B",
            s_suffix: "N/A",
        },
        {
            s_rfid: "0002193309",
            s_studentID: "2023-00069",
            s_fname: "Don Dominick",
            s_mname: "Banagaso",
            s_lname: "Enargan",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "H",
            s_suffix: "Jr.",
        },
        {
            s_rfid: "0002027286",
            s_studentID: "2023-00166",
            s_fname: "John Lyold",
            s_mname: "Castro",
            s_lname: "Lozada",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "A",
            s_suffix: "N/A",
        },
    ];

    const randomUsers = testUsers[Math.floor(Math.random() * testUsers.length)];

    document.getElementById("s_rfid").value = randomUsers.s_rfid;
    document.getElementById("s_studentID").value = randomUsers.s_studentID;
    document.getElementById("s_fname").value = randomUsers.s_fname;
    document.getElementById("s_mname").value = randomUsers.s_mname;
    document.getElementById("s_lname").value = randomUsers.s_lname;
    document.getElementById("s_program").value = randomUsers.s_program;
    document.getElementById("s_lvl").value = randomUsers.s_lvl;
    document.getElementById("s_set").value = randomUsers.s_set;
    document.getElementById("s_suffix").value = randomUsers.s_suffix;
}
