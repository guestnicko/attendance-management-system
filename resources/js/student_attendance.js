import axios from "axios";

console.log("Dev Console ------------ Attendance Page");

let attendanceStart = false;
const form = document.getElementById("attendanceForm");
const form_auto = document.getElementById("auto_attendanceForm");

window.startInterval = startInterval;
window.stopInterval = stopInterval;

// Time Interval to foucs on field for attendance
let intervalId = setInterval(() => {
    console.log("Hello World");
    document.getElementById("inputField1").focus();
}, 500);

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

async function post(form) {
    let isRecorded = false;
    const response = await axios.post(form.get("uri"), form, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "Content-Type": "application/json",
        },
    });
    console.log(response.data);
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
    if (response.isRecorded) {
        console.log(response.data);
        console.log(attendCheckIn);
        console.log(attendCheckOut);
        AttendanceRecorded(objProperty, attendCheckIn, attendCheckOut); //Added 3 arguments to retrieve the data

    } else {
        console.log(response.data);
        AttendanceNotRecorded();
    }

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
    if (response.isRecorded) {
        console.log(response.data);
        console.log(attendCheckIn);
        console.log(attendCheckOut);
        AttendanceRecorded(objProperty, attendCheckIn, attendCheckOut); //Added 3 arguments to retrieve the data
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
function AttendanceRecorded(objProperty, attendCheckIn, attendCheckOut) {
    console.log("Student Attendance Recorded: " + objProperty.s_fname);
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
                <p class="text-md text-gray-500 mt-1">Time In: ${attendCheckIn}</p>
                <p class="text-md text-gray-500 mt-1">Time Out: ${attendCheckOut}</p>

            </div>
        `,
        showConfirmButton: true,
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

function loadTable(data) {
    const table = document.querySelector("#student_table_body");
    table.innerHTML = "";
    data.forEach((element) => {
        table.innerHTML += `
    <tr>
        <td>${element.s_fname + " " + element.s_lname} </td>
        <td>${element.s_program}</td>
        <td>${element.s_set}</td>
        <td>${element.s_lvl}</td>
        <td>${element.attend_checkIn}</td>
        <td>${element.attend_checkOut}</td>
        <td>${formatter.format(new Date(element.created_at))}</td>
    </tr>
    `;
    });
}
