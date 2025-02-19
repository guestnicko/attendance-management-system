window.editEvent = editEvent;
window.deleteEvent = deleteEvent;

function editEvent(data) {
    console.log("editing event");
    console.log(data);
    document.getElementById("evn_name").value = data.event_name;
    document.getElementById("evn_id").value = data.id;
    document.getElementById("evn_date").value = data.date;
    document.getElementById("in_start").value = data.checkIn_start;
    document.getElementById("in_end").value = data.checkIn_end;
    document.getElementById("out_start").value = data.checkOut_start;
    document.getElementById("out_end").value = data.checkOut_end;
    document.getElementById("afternoon_out_end").value =
        data.afternoon_checkOut_end;
    document.getElementById("afternoon_out_start").value =
        data.afternoon_checkOut_start;
    document.getElementById("afternoon_in_start").value =
        data.afternoon_checkIn_start;
    document.getElementById("afternoon_in_end").value =
        data.afternoon_checkIn_end;
    if (data.isWholeDay == "true") {
        document.getElementById("isWholeDay").checked = true;
        afternoon.classList.toggle("hidden");
    }

    // document.getElementById('date').value = data.date;
}

function deleteEvent(data) {
    console.log("deleting event");
    console.log(data);
    document.getElementById("s_id").value = data.id;
    document.getElementById("deleteForm").submit();
}
// FOR MODAL EVENT WHOLE DAY
const afternoon = document.querySelector("#afternoon_attendance");
document.querySelector("#wholeDay").addEventListener("change", function () {
    afternoon.classList.toggle("hidden");
});

document.querySelector("#isWholeDay").addEventListener("change", function () {
    afternoon.classList.toggle("hidden");
});
