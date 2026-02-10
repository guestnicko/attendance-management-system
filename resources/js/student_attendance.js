import axios from "axios";
import Swal from "sweetalert2";
import "./toast_message.js";
let attendanceStart = false;
const form = document.getElementById("attendanceForm");
const form_auto = document.getElementById("auto_attendanceForm");
const withModal = false;
const withToast = true;

// Only set global functions if elements exist
if (typeof window !== "undefined") {
    window.startInterval = startInterval;
    window.stopInterval = stopInterval;
}
let intervalId;
const attendanceLogs = [];

function addLogs(response) {
    if (attendanceLogs.length >= 5) {
        attendanceLogs.shift();
    }
    console.log("Adding to logs:", response);
    insertRow(response);
}

function insertRow(response) {
    const student = response.studentInformation;
    const attendanceInformation = response.attendanceInformation;
    const eventInformation = response.eventInformation;

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
    const row = document.createElement("tr");
    row.classList.add("hover:bg-gray-50", "transition-colors", "duration-200");
    row.innerHTML += `
      <!-- Student ID -->
      <td class="px-2 py-4 max-w-[65px] border-r border-gray-200">
        <span class="text-sm">${student.s_studentID}</span>
      </td>

      <!-- Student Information -->
      <td class="px-6 py-4 border-r border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
              <span class="text-white font-semibold text-sm">
                ${student.s_fname.charAt(0).toUpperCase()}${student.s_lname
                    .charAt(0)
                    .toUpperCase()}
              </span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate text-left">
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

      <!-- Morning Session -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Check In:</span>
            ${
                attendanceInformation.attend_checkIn
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                     ${formatTime(attendanceInformation.attend_checkIn)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Check Out:</span>
            ${
                attendanceInformation.attend_checkOut
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                     ${formatTime(attendanceInformation.attend_checkOut)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
        </div>
      </td>

      ${
          eventInformation && eventInformation.isWholeDay !== "false"
              ? `
      <!-- Afternoon Session -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Afternoon Check In:</span>
            ${
                attendanceInformation.attend_afternoon_checkIn
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                     ${formatTime(attendanceInformation.attend_afternoon_checkIn)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Afternoon Check Out:</span>
            ${
                attendanceInformation.attend_afternoon_checkOut
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                     ${formatTime(
                         attendanceInformation.attend_afternoon_checkOut,
                     )}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
        </div>
      </td>
      `
              : ""
      }

      <!-- Date Column -->
      <td class="px-6 py-4 text-center">
        <div class="text-sm text-gray-900">${formatDate(
            attendanceInformation.created_at,
        )}</div>
      </td>
    `;
    table.prepend(row);
}

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
    document.getElementById("inputField1").focus();
    stopInterval();
    intervalId = setInterval(() => {
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
function formatDate(dateStr) {
    if (!dateStr) return null;
    const date = new Date(dateStr);
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
}

async function post(form) {
    try {
        const response = await axios.post(form.get("uri"), form, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
            },
        });
        return response.data; // return JSON payload
    } catch (error) {
        if (error.response) {
            // Server responded with a status other than 2xx
            console.error("Axios error:", {
                status: error.response.status,
                message: error.response.message,
                headers: error.response.headers,
            });
            // Insert message on pop up
            axiosError(error.response.data.message);
            throw new Error(
                `Request failed with status ${
                    error.response.status
                }: ${JSON.stringify(error.response.data)}`,
            );
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
    event.preventDefault();
    try {
        let inputField = document.querySelector("#inputField"); //Input field HTML element

        // default if input field isn't empty
        const response = await post(new FormData(event.target));
        // Properly fetching data for all responses, and some specific response
        const attendanceInformation = response.attendanceInformation;
        const isUnderMaintenance = response.isUnderMaintenance;
        if (isUnderMaintenance) {
            Swal.fire({
                icon: "warning",
                title: "Under Maintenance",
                text: "Whole day event attendance recording is currently under maintenance.",
                showConfirmButton: true,
            });
            inputField.value = "";
            return;
        }
        let attendCheckIn = attendanceInformation.attend_checkIn;
        let attendCheckOut = attendanceInformation.attend_checkOut;
        let attendAfternoonCheckIn =
            attendanceInformation.attend_afternoon_checkIn; //Fetch the afternoon time-in/out
        let attendAfternoonCheckOut =
            attendanceInformation.attend_afternoon_checkOut;
        if (response.isRecorded) {
            AttendanceRecorded(
                response,
                attendCheckIn,
                attendCheckOut,
                attendAfternoonCheckIn,
                attendAfternoonCheckOut,
            ); //Added 3 arguments to retrieve the data
            addLogs(response);
        } else if (response.AlreadyRecorded) {
            //if student is already recorded, call function
            AttendanceAlreadyRecorded(response);
        } else {
            //If invalid, then call this function
            AttendanceNotRecorded(response);
        }
        // let data = await get();
        // loadTable(data);
        inputField.value = "";
    } catch (error) {
        throw new Error(error);
    }
});
// PREVENT THE FORM FROM SUBMITTING AND REDIRECTING TO A PAGE
form_auto.addEventListener("submit", async (event) => {
    event.preventDefault();
    try {
        let inputField = document.querySelector("#inputField1"); //Input field HTML element

        // default if input field isn't empty
        const response = await post(new FormData(event.target));
        // Properly fetching data for all responses, and some specific response
        // console.log("Auto Response:", response.isUnderMaintenance);
        // const isUnderMaintenance = response.isUnderMaintenance ?? null;
        // if (isUnderMaintenance) {
        //     Swal.fire({
        //         icon: "warning",
        //         title: "Under Maintenance",
        //         text: "Whole day event attendance recording is currently under maintenance.",
        //         showConfirmButton: true,
        //     });
        //     inputField.value = "";
        //     return;
        // }

        inputField.value = "";

        if (response.isRecorded) {
            AttendanceRecorded(response); //Added 3 arguments to retrieve the data
            addLogs(response);
        } else if (response.AlreadyRecorded) {
            //if student is already recorded, call function
            AttendanceAlreadyRecorded(response);
        } else {
            //If invalid, then call this function
            AttendanceNotRecorded(response);
        }
        // let data = await get();
        // loadTable(data);
    } catch (error) {
        throw new Error(error);
    }
});
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

// ENHANCE THE POP UP TO SHOW THE DETAILS OF THE STUDENT AND ITS CHECKIN AND OUT
// ADDED ADDITIONAL TWO PARAMETERS FOR AFTERNOON DETAILS
function axiosError(message) {
    Swal.fire({
        icon: "warning",
        title: "Axios Error",
        text: message,
        showConfirmButton: true,
        // timer: 500,
    });
}

function AttendanceRecorded(responses) {
    console.log("Recorded Response:", responses);
    const studentInformation = responses.studentInformation;
    const attendanceInformation = responses.attendanceInformation;
    // Dynamically build time sections
    let timeInfo = "";

    if (attendanceInformation.attendCheckIn) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-1 font-semibold">Morning Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time In: ${formatTime(
                attendanceInformation.attendCheckIn,
            )}</p>
        `;
    }

    if (attendanceInformation.attendCheckOut) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-1 font-semibold">Morning Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time Out: ${formatTime(
                attendanceInformation.attendCheckOut,
            )}</p>
        `;
    }

    if (attendanceInformation.attend_afternoon_checkIn) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-4 font-semibold">Afternoon Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time In: 1:27 PM ${formatTime(
                attendanceInformation.attend_afternoon_checkIn,
            )}</p>
        `;
    }
    if (attendanceInformation.attend_afternoon_checkOut) {
        timeInfo += `
            <p class="text-md text-gray-500 mt-4 font-semibold">Afternoon Attendance</p>
            <p class="text-md text-gray-500 mt-1">Time Out: ${formatTime(
                attendanceInformation.attend_afternoon_checkOut,
            )}</p>
        `;
    }
    if (withToast) {
        displayToast(responses);
    }
    if (withModal) {
        Swal.fire({
            icon: "success",
            title: "Attendance Recorded!",
            html: `
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Welcome,</h2>
                <h3 class="text-3xl font-bold text-red-600 my-2">
                    ${studentInformation.s_fname} ${studentInformation.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${studentInformation.s_program} - Year Level: ${studentInformation.s_lvl}
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${studentInformation.s_set}</p>
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
}

function AttendanceNotRecorded(data) {
    Swal.fire({
        icon: "warning",
        title: "Fetching Error!",
        text: data.message,
        showConfirmButton: true,
        // timer: 500,
    });
}

// Function to show a pop-up about a student is already recorded
function AttendanceAlreadyRecorded(responses) {
    const studentInformation = responses.studentInformation;
    const attendanceInformation = responses.attendanceInformation;
    console.log("Already Recorded Response:", responses);
    console.log("Attendance Information:", attendanceInformation);
    Swal.fire({
        icon: "warning",
        title: "Student is already recorded!",
        html: `
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Details:</h2>
                <h3 class="text-3xl font-bold text-red-600 my-2">
                    ${studentInformation.s_fname} ${studentInformation.s_lname}
                </h3>
                <p class="text-lg font-medium text-gray-700">
                    ${studentInformation.s_program} - Year Level: ${
                        studentInformation.s_lvl
                    }
                </p>
                <p class="text-md text-gray-500 mt-1">Set: ${
                    studentInformation.s_set
                }</p>

                <p class="text-md text-gray-500 mt-1">Time In (Morning): ${
                    formatTime(attendanceInformation.attend_checkIn) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Morning): ${
                    formatTime(attendanceInformation.attend_checkOut) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time In (Afternoon): ${
                    formatTime(
                        attendanceInformation.attend_afternoon_checkIn,
                    ) ?? "---"
                }</p>
                <p class="text-md text-gray-500 mt-1">Time Out (Afternoon): ${
                    formatTime(
                        attendanceInformation.attend_afternoon_checkOut,
                    ) ?? "---"
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
    data.students.forEach((student) => {
        table.innerHTML += `
    <tr class="hover:bg-gray-50 transition-colors duration-200">
      <!-- Student ID -->
      <td class="px-2 py-4 max-w-[65px] border-r border-gray-200">
        <span class="text-sm">${student.s_studentID}</span>
      </td>

      <!-- Student Information -->
      <td class="px-6 py-4 border-r border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
              <span class="text-white font-semibold text-sm">
                ${student.s_fname.charAt(0).toUpperCase()}${student.s_lname
                    .charAt(0)
                    .toUpperCase()}
              </span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate text-left">
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

      <!-- Morning Session -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Morning Check In:</span>
            ${
                student.attend_checkIn
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                     ${formatTime(student.attend_checkIn)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Morning Check Out:</span>
            ${
                student.attend_checkOut
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                     ${formatTime(student.attend_checkOut)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
        </div>
      </td>

      ${
          event && event.isWholeDay !== "false"
              ? `
      <!-- Afternoon Session -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Check In:</span>
            ${
                student.attend_afternoon_checkIn
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    1:27 PM
                     ${formatTime(student.attend_afternoon_checkIn)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
          <div class="flex items-center justify-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Check Out:</span>
            ${
                student.attend_afternoon_checkOut
                    ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                     ${formatTime(student.attend_afternoon_checkOut)}
                   </span>`
                    : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg> Absent
                   </span>`
            }
          </div>
        </div>
      </td>
      `
              : ""
      }

      <!-- Date Column -->
      <td class="px-6 py-4 text-center">
        <div class="text-sm text-gray-900">${formatDate(
            student.created_at,
        )}</div>
      </td>
    </tr>
    `;
    });
}

// Clean up interval when page unloads
window.addEventListener("beforeunload", () => {
    stopInterval();
});
