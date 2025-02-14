import axios from "axios";

const api = axios.create({
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
});

window.GenerateExcelReport = GenerateExcelReport;
window.GeneratePDFReport = GeneratePDFReport;
document.getCategory = getCategory;
const baseUrl = window.location.href;

document.querySelector("#eventField").addEventListener("change", (e) => {
    document.querySelector("#inputField").value = e.target.value;
    getCategory();
});

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

async function getCategory() {
    console.log("working");
    const program = document.querySelectorAll(
        '#search_program input[name="program"]:checked'
    );
    const lvl = document.querySelectorAll(
        '#search_lvl input[name="lvl"]:checked'
    );
    const set = document.querySelectorAll(
        '#search_set input[name="set"]:checked'
    );

    const status = document.querySelectorAll(
        '#search_status input[name="status "]:checked'
    );
    let uri = baseUrl + "/category?";
    const program_data = Array.from(program).map((cb) => cb.value);
    const lvl_data = Array.from(lvl).map((cb) => cb.value);
    const set_data = Array.from(set).map((cb) => cb.value);
    const status_data = Array.from(status).map((cb) => cb.value);
    const event_id = document.querySelector("#eventField").value;

    uri += "&&program=";
    program_data.forEach((element) => {
        uri += element + ",";
        document.querySelector("#programField").value += element;
    });
    uri += "&&lvl=";

    lvl_data.forEach((element) => {
        uri += element + ",";
        document.querySelector("#lvlField").value += element;
    });
    uri += "&&set=";

    set_data.forEach((element) => {
        uri += element + ",";
        document.querySelector("#setField").value += element;
    });
    uri += "&&status=";

    status_data.forEach((element) => {
        uri += element + ",";
        document.querySelector("#statusField").value += element;
    });

    uri += "&&event_id=" + event_id;

    const data = await api.get(uri);
    // UPDATE TABLE
    const students = data.data.students;
    const table = document.getElementById("student_table_body");
    table.innerHTML = "";
    if (students) {
        let i = 1;
        students.forEach((e) => {
            table.innerHTML += `
                        <tr>
                            <td>${i++}</td>
                            <td>${e.s_fname} ${e.s_lname}</td>
                            <td>${e.s_program}</td>
                            <td>${e.s_set}</td>
                            <td>${e.s_lvl}</td>

                            <td>${e.attend_checkIn}</td>
                            <td>${e.attend_checkOut}</td>
                            <td>${e.event_name}</td>
                            <td>${formatter.format(new Date(e.created_at))}</td>
                        </tr>
            `;
            document.getElementById("std_info_table").innerHTML = "";
        });
    } else {
        document.getElementById("std_info_table").innerHTML = `
<h3 class="text-center">No Student Found</h3>
`;
    }
}
