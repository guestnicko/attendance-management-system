import axios from "axios";

console.log("Dev Console ------------ Attendance Page");

let attendanceStart = false;
const form = document.getElementById("attendanceForm");
const form_auto = document.getElementById("auto_attendanceForm");

// Only set global functions if elements exist
if (typeof window !== "undefined") {
    window.startInterval = startInterval;
    window.stopInterval = stopInterval;
}

const inputField1 = document.getElementById("inputField1");

let intervalId;

// Time Interval to foucs on field for attendance
// if (inputField1) {
//     intervalId = setInterval(() => {
//         console.log("Attendance ongoing!");
//         inputField1.focus();
//     }, 1000);
// }


const formatter = new Intl.DateTimeFormat("ja-JP", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
});

function stopInterval() {
    if (intervalId) {
        clearInterval(intervalId);
        console.log("Interval stopped!");
    }
}

function startInterval() {
    stopInterval();
    intervalId = setInterval(() => {
        console.log("Hello World");
        document.getElementById("inputField1").focus();
    }, 1000);

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
    try {
        const response = await axios.post(form.get("uri"), form, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json",
            },
        });
        return response.data; // return JSON payload
    } catch (error) {
        if (error.response) {
            // Server responded with a status other than 2xx
            console.error("Axios error:", {
                status: error.response.status,
                data: error.response.data,
                headers: error.response.headers,
            });
            // Insert message on pop up
            axiosError(error.response.data.message);
            throw new Error(`Request failed with status ${error.response.status}: ${JSON.stringify(error.response.data)}`);
        } else if (error.request) {
            // Request made but no response received
            console.error("No response received:", error.request);
            throw new Error("No response received from server.");
        } else {
            // Something else happened while setting up the request
            console.error("Error setting up request:", error.message);
            throw new Error("Error: " + error.message);
        }
    }
}


// PREVENT THE FORM FROM SUBMITTING AND REDIRECTING TO A PAGE
form.addEventListener("submit", async (event) => {
    try {
        event.preventDefault();
        let inputField = document.querySelector("#inputField"); //Input field HTML element

        // default if input field isn't empty
        const response = await post(new FormData(event.target));
        // Properly fetching data for all responses, and some specific response
        let objProperty = response;
        let attendCheckIn = response.attend_checkIn;
        let attendCheckOut = response.attend_checkOut;
        let attendAfternoonCheckIn = response.attend_afternoon_checkIn; //Fetch the afternoon time-in/out
        let attendAfternoonCheckOut = response.attend_afternoon_checkOut;
        if (response.isRecorded) {
            AttendanceRecorded(
                response,
                attendCheckIn,
                attendCheckOut,
                attendAfternoonCheckIn,
                attendAfternoonCheckOut
            ); //Added 3 arguments to retrieve the data
            console.log(response)
        } else if (response.AlreadyRecorded) {
            //if student is already recorded, call function
            console.log("Already recorded | Data: ", response);
            AttendanceAlreadyRecorded(response);
        } else {
            //If invalid, then call this function
            console.log(response);
            AttendanceNotRecorded(response);
        }
        let data = await get();
        loadTable(data);

        
        inputField.value = "";
    } catch (error) {
        throw new Error(error);
    }
    // notify(isRecorded, "")


// PREVENT THE FORM FROM SUBMITTING AND REDIRECTING TO A PAGE
// form_auto.addEventListener("submit", async (event) => {
//     event.preventDefault();
//     let response = await post(new FormData(event.target));
//     // 3 VARIABLES ARE USED TO FETCH JSON DATA
//     let objProperty = response.data;
//     let attendCheckIn = response.attend_checkIn;
//     let attendCheckOut = response.attend_checkOut;
//     let attendAfternoonCheckIn = response.attend_afternoon_checkIn; //Fetch the afternoon time-in/out
//     let attendAfternoonCheckOut = response.attend_afternoon_checkOut;
//     if (response.isRecorded) {
//         AttendanceRecorded(
//             objProperty,
//             attendCheckIn,
//             attendCheckOut,
//             attendAfternoonCheckIn,
//             attendAfternoonCheckOut
//         ); //Added 3 arguments to retrieve the data
//     } else if (response.AlreadyRecorded) {
//         //if student is already recorded, call function
//         console.log("You are already recorded");
//         AttendanceAlreadyRecorded(objProperty, response.data);
//     } else {
//         console.log(response.data);
//         AttendanceNotRecorded();
//     }
//     let data = await get();
//     loadTable(data);
//     document.querySelector("#inputField1").value = "";
//     // notify(isRecorded, "")

//     // notify(isFetch, "")
// });

// LOAD THE TABLE => GET
async function get() {
    const getURIElement = document.getElementById("getURI");
    if (!getURIElement) {
        console.warn("getURI element not found");
        return { students: [] };
    }

    let uri = getURIElement.value;
    let isFetch = false;
    try {
        const data = await axios.get(uri);
        return data.data;
    } catch (error) {
        console.error("Error fetching data:", error);
        return { students: [] };
    }
}

// FOR NOTIFICATIONS ETC

function notify(status, content) {}

function error(status, content) {}

// ENHANCE THE POP UP TO SHOW THE DETAILS OF THE STUDENT AND ITS CHECKIN AND OUT
// ADDED ADDITIONAL TWO PARAMETERS FOR AFTERNOON DETAILS
function axiosError(message){
    Swal.fire({
        icon: "warning",
        title: "Axios Error",
        text: message,
        showConfirmButton: true,
        // timer: 500,
    });
}

function AttendanceRecorded(
    responses,
    attendCheckIn,
    attendCheckOut,
    attend_afternoon_checkIn,
    attend_afternoon_checkOut
) {
    console.log("Student Attendance Recorded: " + responses.data.s_fname);

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
                    ${responses.data.s_fname} ${responses.data.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${responses.data.s_program} - Year Level: ${responses.data.s_lvl}
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${responses.data.s_set}</p>
                ${timeInfo}
            </div>
        `,
        showConfirmButton: true,
        // timer: 500,
        customClass: {
            popup: "bg-white shadow-lg rounded-xl p-6",
            title: "text-xl font-bold text-gray-900",
        },
    });
}

function AttendanceNotRecorded(data) {
    console.log(data.message);
    Swal.fire({
        icon: "warning",
        title: "Fetching Error!",
        text: data.message ,
        showConfirmButton: true,
        // timer: 500,
    });
}

// Function to show a pop-up about a student is already recorded
function AttendanceAlreadyRecorded(responses) {

    Swal.fire({
        icon: "warning",
        title: "Student is already recorded!",
        html: `
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Details:</h2>
                <h3 class="text-3xl font-bold text-red-600 my-2">
                    ${responses.data.s_fname} ${responses.data.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${responses.data.s_program} - Year Level: ${responses.data.s_lvl}
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${
                    responses.data.s_set
                }</p>

                <p class="text-md text-gray-500 mt-1">Time In (Morning): ${
                    formatTime(responses.event_data.attend_checkIn) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Morning): ${
                    formatTime(responses.event_data.attend_checkOut) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time In (Afternoon): ${
                    formatTime(responses.event_data.attend_afternoon_checkIn) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Afternoon): ${
                    formatTime(responses.event_data.attend_afternoon_checkOut) ?? "---"
                }</p>
            </div>
        `,
        showConfirmButton: true,
        // timer: 500,
    });
}

function loadTable(data) {
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
    if (!table) {
        console.warn("student_table_body not found");
        return;
    }

    table.innerHTML = "";
    data.students.forEach((element) => {
        // console.log(element);
        table.innerHTML += `
    <tr>
        <td class="py-2">${element.s_fname + " " + element.s_lname} </td>
        <td>${element.s_program}</td>
        <td>${element.s_set}</td>
        <td>${element.s_lvl}</td>
        <td>${
            element.attend_checkIn
                ? formatTime(element.attend_checkIn)
                : '<span class="text-red-500">Absent</span>'
        }</td>
        <td>${
            element.attend_checkOut
                ? formatTime(element.attend_checkOut)
                : '<span class="text-red-500">Absent</span>'
        }</td>
        ${checkType(data.event.isWholeDay, element)}


        <td>${formatter.format(new Date(element.created_at))}</td>
    </tr>
    `;
    });
}

// Clean up interval when page unloads
window.addEventListener("beforeunload", () => {
    stopInterval();
});
