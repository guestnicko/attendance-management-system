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

function formatDate(dateStr) {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
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

    if (students) {
        students.forEach((student, index) => {
            table.innerHTML += createFineRow(student);
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

function createFineRow(fine) {
    // Helper for missed actions
    function missedSpan(label) {
        return `
      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-3 h-3 mr-1">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
        ${label}
      </span>
    `;
    }

    // Missed actions section
    const missedActions =
        fine.morning_checkIn_missed ||
        fine.morning_checkOut_missed ||
        fine.afternoon_checkIn_missed ||
        fine.afternoon_checkOut_missed
            ? `
        <div class="space-y-1">
          ${fine.morning_checkIn_missed ? missedSpan("Morning Check-in") : ""}
          ${fine.morning_checkOut_missed ? missedSpan("Morning Check-out") : ""}
          ${
              fine.afternoon_checkIn_missed
                  ? missedSpan("Afternoon Check-in")
                  : ""
          }
          ${
              fine.afternoon_checkOut_missed
                  ? missedSpan("Afternoon Check-out")
                  : ""
          }
        </div>
      `
            : `<span class="text-xs text-gray-500">No missed actions</span>`;

    return `
    <tr class="hover:bg-gray-50 transition-colors duration-200">
      <!-- Student Info -->
      <td class="px-6 py-4 border-r border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
              <span class="text-white font-semibold text-sm">
                ${fine.s_fname.charAt(0).toUpperCase()}${fine.s_lname
        .charAt(0)
        .toUpperCase()}
              </span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">
              ${fine.s_fname} ${fine.s_lname}
            </p>
            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
              <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                ${fine.s_program}
              </span>
              <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                Set ${fine.s_set}
              </span>
              <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                Year ${fine.s_lvl}
              </span>
            </div>
          </div>
        </div>
      </td>

      <!-- Missed Actions -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">${missedActions}</div>
      </td>

      <!-- Fine Details -->
      <td class="px-6 py-4 text-center border-r border-gray-200">
        <div class="space-y-2">
          <div class="flex items-center justify-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
              ${formatCurrency(fine.fines_amount)}
            </span>
          </div>
          <div class="flex items-center justify-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 font-bold">
              ${formatCurrency(fine.total_fines)}
            </span>
          </div>
        </div>
      </td>

      <!-- Event Details -->
      <td class="px-6 py-4 text-center">
        <div class="space-y-2">
          <div class="flex items-center justify-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-4 h-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
              </svg>
              ${fine.event_name || "-"}
            </span>
          </div>
          <div class="text-xs text-gray-500">
            ${formatDate(fine.created_at)}
          </div>
        </div>
      </td>
    </tr>
  `;
}
