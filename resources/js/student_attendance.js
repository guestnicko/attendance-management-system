import axios from "axios";

console.log("Dev Console ------------ Attendance Page");

let attendanceStart = false;
const form = document.getElementById("attendanceForm");
const form_auto = document.getElementById("auto_attendanceForm");

window.startInterval = startInterval;
window.stopInterval = stopInterval;
const inputField1 = document.getElementById("inputField1");

let intervalId;

// Time Interval to foucs on field for attendance
if (inputField1) {
    intervalId = setInterval(() => {
        console.log("Hello World");
        inputField1.focus();
    }, 500);
}

const formatter = new Intl.DateTimeFormat("ja-JP", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
});

function stopInterval() {
    clearInterval(intervalId);
    console.log("Interval stopped!");
}

function startInterval() {
    stopInterval();
    intervalId = setInterval(() => {
        console.log("Hello World");
        document.getElementById("inputField1").focus();
    }, 500);
}

// Formats time to user-friendly format
function formatTime(timeStr) {
    if (!timeStr) return "---";
    const date = new Date(`1970-01-01T${timeStr}`); // Attach dummy date for parsing
    return date.toLocaleTimeString([], {
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
    });
}

async function post(form) {
    let isRecorded = false;
    const response = await axios.post(form.get("uri"), form, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "Content-Type": "application/json",
        },
    });
    return response.data;
}

// PREVENT THE FORM FROM SUBMITTING AND REDIRECTING TO A PAGE
form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const response = await post(new FormData(event.target));
    // 3 VARIABLES ARE USED TO FETCH JSON DATA
    let objProperty = response.data;
    let attendCheckIn = response.attend_checkIn;
    let attendCheckOut = response.attend_checkOut;
    let attendAfternoonCheckIn = response.attend_afternoon_checkIn; //Fetch the afternoon time-in/out
    let attendAfternoonCheckOut = response.attend_afternoon_checkOut;
    if (response.isRecorded) {
        AttendanceRecorded(
            objProperty,
            attendCheckIn,
            attendCheckOut,
            attendAfternoonCheckIn,
            attendAfternoonCheckOut
        ); //Added 3 arguments to retrieve the data
    } else if (response.AlreadyRecorded) {
        //if student is already recorded, call function
        console.log("Already recorded | Data: ", response.event_data);
        AttendanceAlreadyRecorded(objProperty, response.event_data);
    } else {
        //If invalid, then call this function

        console.log(response.data);
        AttendanceNotRecorded();
    }
    let data = await get();
    loadTable(data);
    document.querySelector("#inputField").value = "";
    // notify(isRecorded, "")

    // notify(isFetch, "")
});
// PREVENT THE FORM FROM SUBMITTING AND REDIRECTING TO A PAGE
form_auto.addEventListener("submit", async (event) => {
    event.preventDefault();
    let response = await post(new FormData(event.target));
    // 3 VARIABLES ARE USED TO FETCH JSON DATA
    let objProperty = response.data;
    let attendCheckIn = response.attend_checkIn;
    let attendCheckOut = response.attend_checkOut;
    let attendAfternoonCheckIn = response.attend_afternoon_checkIn; //Fetch the afternoon time-in/out
    let attendAfternoonCheckOut = response.attend_afternoon_checkOut;
    if (response.isRecorded) {
        AttendanceRecorded(
            objProperty,
            attendCheckIn,
            attendCheckOut,
            attendAfternoonCheckIn,
            attendAfternoonCheckOut
        ); //Added 3 arguments to retrieve the data
    } else if (response.AlreadyRecorded) {
        //if student is already recorded, call function
        console.log("You are already recorded");
        AttendanceAlreadyRecorded(objProperty, response.data);
    } else {
        console.log(response.data);
        AttendanceNotRecorded();
    }
    let data = await get();
    loadTable(data);
    document.querySelector("#inputField1").value = "";
    // notify(isRecorded, "")

    // notify(isFetch, "")
});
// LOAD THE TABLE => GET
async function get() {
    let uri = document.getElementById("getURI").value;
    let isFetch = false;
    const data = await axios.get(uri);
    return data.data;
}

// FOR NOTIFICATIONS ETC

function notify(status, content) {}

function error(status, content) {}

// ENHANCE THE POP UP TO SHOW THE DETAILS OF THE STUDENT AND ITS CHECKIN AND OUT
// ADDED ADDITIONAL TWO PARAMETERS FOR AFTERNOON DETAILS
function AttendanceRecorded(
    objProperty,
    attendCheckIn,
    attendCheckOut,
    attend_afternoon_checkIn,
    attend_afternoon_checkOut
) {
    console.log("Student Attendance Recorded: " + objProperty.s_fname);

    // Dynamically build time sections
    let timeInfo = "";

    if (attendCheckIn || attendCheckOut) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-1 font-semibold">Morning Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time In: ${formatTime(
                attendCheckIn
            )}</p>
        `;
    }

    if (attendCheckOut) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-1 font-semibold">Morning Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time Out: ${formatTime(
                attendCheckOut
            )}</p>
        `;
    }

    if (attend_afternoon_checkIn) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-4 font-semibold">Afternoon Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time In: ${formatTime(
                attend_afternoon_checkIn
            )}</p>
        `;
    }
    if (attend_afternoon_checkOut) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-4 font-semibold">Afternoon Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time Out: ${formatTime(
                attend_afternoon_checkOut
            )}</p>
        `;
    }

    Swal.fire({
        icon: "success",
        title: "Attendance Recorded!",
        html: `
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Welcome,</h2>
                <h3 class="text-3xl font-bold text-red-600 my-2">
                    ${objProperty.s_fname} ${objProperty.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${objProperty.s_program} - Year Level: ${objProperty.s_lvl}
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${objProperty.s_set}</p>
                ${timeInfo}
            </div>
        `,
        showConfirmButton: true,
        // timer: 1500,
        customClass: {
            popup: "bg-white shadow-lg rounded-xl p-6",
            title: "text-xl font-bold text-gray-900",
        },
    });
}

function AttendanceNotRecorded() {
    console.log("Student Attendance Not Recorded");
    Swal.fire({
        icon: "warning",
        title: "Student Attendance Not Recorded!",
        showConfirmButton: false,
        timer: 1500,
    });
}

// Function to show a pop-up about a student is already recorded
function AttendanceAlreadyRecorded(objProperty, eventData) {

    Swal.fire({
        icon: "warning",
        title: "Student is already recorded!",
        html: `
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Details:</h2>
                <h3 class="text-3xl font-bold text-red-600 my-2">
                    ${objProperty.s_fname} ${objProperty.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${objProperty.s_program} - Year Level: ${objProperty.s_lvl}
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${
                    objProperty.s_set
                }</p>

                <p class="text-md text-gray-500 mt-1">Time In (Morning): ${
                    formatTime(eventData.attend_checkIn) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Morning): ${
                    formatTime(eventData.attend_checkOut) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time In (Afternoon): ${
                    formatTime(eventData.attend_afternoon_checkIn) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Afternoon): ${
                    formatTime(eventData.attend_afternoon_checkOut) ?? "---"
                }</p>
            </div>
        `,
        showConfirmButton: false,
        timer: 1500,
    });
}

function loadTable(data) {
    console.log(data);
    const checkType = (type, element) => {
        // Returns afternoon column
        if (type == "true") {
            return `<td>${
                element.attend_afternoon_checkIn
                    ? formatTime(element.attend_afternoon_checkIn)
                    : '<span class="text-red-500">Absent</span>'
            }</td>
            <td>${
                element.attend_afternoon_checkOut
                    ? formatTime(element.attendAfternoonCheckOut)
                    : '<span class="text-red-500">Absent</span>'
            }</td>`;
        }
        return "";
    };

    const table = document.querySelector("#student_table_body");
    table.innerHTML = "";
    data.students.forEach((element) => {
        console.log(element);
        table.innerHTML += `
    <tr>
        <td class="py-2">${element.s_fname + " " + element.s_lname} </td>
        <td>${element.s_program}</td>
        <td>${element.s_set}</td>
        <td>${element.s_lvl}</td>
        <td>${element.attend_checkIn ? formatTime(element.attend_checkIn) : '<span class="text-red-500">Absent</span>'}</td>
        <td>${element.attend_checkOut ? formatTime(element.attend_checkOut) : '<span class="text-red-500">Absent</span>'}</td>
        ${checkType(data.event.isWholeDay, element)}


        <td>${formatter.format(new Date(element.created_at))}</td>
    </tr>
    `;
    });
}