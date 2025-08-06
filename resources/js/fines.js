window.renderTable = renderTable;
function renderTable(students) {
    const table = document.getElementById("student_table_body");
    table.innerHTML = "";
    console.log(students);
    if (students) {
        students.forEach((student, index) => {
            const date = new Date(student.created_at); // Or your date object
            const formatter = new Intl.DateTimeFormat("en-US", {
                // 'en-US' for US format, adjust as needed
                year: "numeric",
                month: "long",
                day: "2-digit",
            });
            const formattedDate = formatter.format(date);
            let template = ` <tr class="table_row shadow-lg border-3">
                            <td class="py-5">${(index += 1)}</td>
                            <td>${student.s_fname} ${student.s_lname}</td>
                            <td>${student.s_program}</td>
                            <td>${student.s_set}</td>
                            <td>${student.s_lvl}</td>
                            <td>
                                <ul class="text-center text-red-700 font-mono text-base">`;
            if (student.morning_checkIn_missed) {
                template += `<li>Morning Check-in</li>`;
            }
            if (student.morning_checkOut_missed) {
                template += `<li>Morning Check-out</li>`;
            }
            if (student.afternoon_checkIn_missed) {
                template += `<li>Afternoon Check-in</li>`;
            }
            if (student.afternoon_checkOut_missed) {
                template += `<li>Afternoon Check-out</li>`;
            }
            template += `</ul>
                            </td>
                            <td>₱${student.fines_amount.toFixed(2)}</td>
                            <td>₱${student.total_fines.toFixed(2)}</td>
                            <td>${student.event_name}</td>
                            <td>${formattedDate}</td>
                        </tr>`;
            table.innerHTML += template;
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
