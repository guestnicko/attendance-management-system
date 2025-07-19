import axios from "axios";

const api = axios.create({
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
});

window.GenerateExcelReport = GenerateExcelReport;
window.GeneratePDFReport = GeneratePDFReport;

const formatter = new Intl.DateTimeFormat("ja-JP", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
});

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
        let i = 1;
        students.forEach((e) => {
            const date = new Date(e.created_at); // Or your date object
            const formatter = new Intl.DateTimeFormat("en-US", {
                // 'en-US' for US format, adjust as needed
                year: "numeric",
                month: "long",
                day: "2-digit",
            });
            const formattedDate = formatter.format(date);
            table.innerHTML += `
                        <tr class= "table_row shadow-lg border-3">
                            <td class="py-5">${i++}</td>
                            <td>${e.s_fname} ${e.s_lname}</td>
                            <td>${e.s_program}</td>
                            <td>${e.s_set}</td>
                            <td>${e.s_lvl}</td>

                            <td>${
                                e.attend_checkIn ??
                                '<span class="text-red-500">Absent</span>'
                            }</td>
                            <td>${
                                e.attend_checkOut ??
                                '<span class="text-red-500">Absent</span>'
                            }</td>
                            <td>${
                                e.attend_afternoon_checkIn ??
                                '<span class="text-red-500">Absent</span>'
                            }</td>
                            <td>${
                                e.attend_afternoon_checkOut ??
                                '<span class="text-red-500">Absent</span>'
                            }</td>
                            <td>${e.event_name}</td>
                            <td>${formattedDate}</td>
                        </tr>
            `;
            document.getElementById("std_info_table").style.display = "none"; //Line by Panzerweb: When search is empty, remove the span
            i++;
        });
    } else {
        //Code by Panzerweb: If search does not match, display text 'No Student Found'
        document.getElementById("std_info_table").style.display = "block";
        document.getElementById(
            "std_info_table"
        ).innerHTML = `<h3 class="py-4 text-center tracking-wide text-gray-500 text-xl">No Student Found</h3>`;
    }
}
