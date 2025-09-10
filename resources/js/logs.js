import axios from "axios";

const api = axios.create({
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
});

window.GenerateExcelReport = GenerateExcelReport;
window.GeneratePDFReport = GeneratePDFReport;

function formatCurrency(amount) {
    if (!amount) return "-";
    return `₱${Number(amount).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
    })}`;
}
function parseDateString(dateString) {
    if (!dateString) return null;

    // Already a Date object
    if (dateString instanceof Date) return dateString;

    // Case: Only time is given ("hh:mm:ss")
    if (/^\d{2}:\d{2}:\d{2}$/.test(dateString)) {
        const today = new Date().toISOString().split("T")[0]; // "YYYY-MM-DD"
        dateString = `${today}T${dateString}`; // attach today's date
    }

    // Case: MySQL datetime "YYYY-MM-DD hh:mm:ss"
    if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(dateString)) {
        dateString = dateString.replace(" ", "T");
    }

    const date = new Date(dateString);
    return isNaN(date.getTime()) ? null : date;
}

function formatDate(dateString) {
    const date = parseDateString(dateString);
    if (!date) return "-";
    return date.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
}

function GeneratePDFReport() {
    document.querySelector("#fileType").value = "pdf";
    document.querySelector("#exportForm").submit();
}

function GenerateExcelReport() {
    document.querySelector("#fileType").value = "excel";
    document.querySelector("#exportForm").submit();
}

window.renderTable = renderTable;
function renderTable(students) {
    const table = document.getElementById("student_table_body");
    table.innerHTML = "";
    console.log(students);
    if (students) {
        students.forEach((student, index) => {
            table.innerHTML += createLogRow(student);
            document.getElementById("std_info_table").style.display = "none"; //Line by Panzerweb: When search is empty, remove the span
        });
    } else {
        //Code by Panzerweb: If search does not match, display text 'No Student Found'
        document.getElementById("std_info_table").style.display = "block";
        document.getElementById(
            "std_info_table"
        ).innerHTML = `<h3 class="py-4 text-center tracking-wide text-gray-500 text-xl">No Student Found</h3>`;
    }
}

function createLogRow(log) {
    return `
    <tr class="hover:bg-gray-50 transition-colors duration-200">
  <!-- Student Information Column -->
  <td class="px-6 py-4 border-r border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="flex-shrink-0">
        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
          <span class="text-white font-semibold text-sm">
            ${log.s_fname[0].toUpperCase()}${log.s_lname[0].toUpperCase()}
          </span>
        </div>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-gray-900">
          ${log.s_fname} ${log.s_lname}
        </p>
        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
          <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
            ${log.s_program}
          </span>
          <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
            Set ${log.s_set}
          </span>
          <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
            Year ${log.s_lvl}
          </span>
        </div>
      </div>
    </div>
  </td>

  <!-- Time of Actions Column -->
  <td class="px-6 py-4 text-center border-r border-gray-200">
    <div class="space-y-2">
      ${
          log.attend_checkIn ||
          log.attend_checkOut ||
          log.attend_afternoon_checkIn ||
          log.attend_afternoon_checkOut
              ? `
            ${
                log.attend_checkIn
                    ? `<span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Morning Check-in <br>               ${formatDate(
                  log.attend_checkIn
              )}

            </span><br>`
                    : ""
            }

            ${
                log.attend_checkOut
                    ? `<span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Morning Check-out <br>               ${formatDate(
                  log.attend_checkOut
              )}

            </span><br>`
                    : ""
            }

            ${
                log.attend_afternoon_checkIn
                    ? `<span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Afternoon Check-in <br>               ${formatDate(
                  log.attend_afternoon_checkIn
              )}

            </span><br>`
                    : ""
            }

            ${
                log.attend_afternoon_checkOut
                    ? `<span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Afternoon Check-out <br>
              ${formatDate(log.attend_afternoon_checkOut)}

            </span><br>`
                    : ""
            }
          `
              : `<span class="text-xs text-gray-500">No missed actions</span>`
      }
    </div>
  </td>

  <!-- Missed Actions Column -->
  <td class="px-6 py-4 text-center border-r border-gray-200">
    <div class="space-y-2">
      ${
          !log.attend_checkIn ||
          !log.attend_checkOut ||
          (!log.attend_afternoon_checkIn && log.isWholeDay !== "false") ||
          (!log.attend_afternoon_checkOut && log.isWholeDay !== "false")
              ? `
            ${
                !log.attend_checkIn
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg> Morning Check-in</span><br>`
                    : ""
            }
            ${
                !log.attend_checkOut
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg> Morning Check-out</span><br>`
                    : ""
            }
            ${
                log.isWholeDay != "false" && !log.attend_afternoon_checkIn
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg> Afternoon Check-in</span><br>`
                    : ""
            }
            ${
                log.isWholeDay != "false" && !log.attend_afternoon_checkOut
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-3 h-3 mr-1">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg> Afternoon Check-out</span><br>`
                    : ""
            }
          `
              : `<span class="text-xs text-gray-500">No missed actions</span>`
      }
    </div>
  </td>

  <!-- Event Details Column -->
  <td class="px-6 py-4 text-center">
    <div class="space-y-2">
      <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
          📅 ${log.event_name || "-"}
        </span>
      </div>
      <div class="text-xs text-gray-500">
        ${
            log.created_at
                ? new Date(log.created_at).toLocaleDateString("en-US", {
                      month: "short",
                      day: "numeric",
                      year: "numeric",
                  })
                : "-"
        }
      </div>
    </div>
  </td>
</tr>`;
}
