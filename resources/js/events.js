window.editEvent = editEvent;
window.deleteEvent = deleteEvent;

function editEvent(data) {
    console.log("editing event");
    console.log(data);
    document.getElementById("evn_name").value = data.event_name;
    document.getElementById("evn_id").value = data.id;
    document.getElementById("evn_date").value = data.date;
    document.getElementById("evn_fines").value = data.fines_amount;
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
        afternoon.classList.remove("hidden");
    } else {
        document.getElementById("isWholeDay").checked = false;
        afternoon.classList.add("hidden");
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
    document
        .querySelector("#create_afternoon_attendance")
        .classList.toggle("hidden");
});

document.querySelector("#isWholeDay").addEventListener("change", function () {
    afternoon.classList.toggle("hidden");
});

function navigateTab(table, button) {
    if (table == "pendingEventTable") {
        document.getElementById("completedEventTable").classList.add("hidden");
        document.getElementById("deletedEventTable").classList.add("hidden");

        document.getElementById("pendingEventTable").classList.remove("hidden");
    } else if (table == "completedEventTable") {
        document
            .getElementById("completedEventTable")
            .classList.remove("hidden");
        document.getElementById("pendingEventTable").classList.add("hidden");
        document.getElementById("deletedEventTable").classList.add("hidden");
    } else if (table == "deletedEventTable") {
        document.getElementById("deletedEventTable").classList.remove("hidden");
        document.getElementById("pendingEventTable").classList.add("hidden");
        document.getElementById("completedEventTable").classList.add("hidden");
    }
    if (button == "pendingEventButton") {
        document
            .getElementById("completedEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("pendingEventButton")
            .classList.add("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("deletedEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
    } else if (button == "completedEventButton") {
        document
            .getElementById("completedEventButton")
            .classList.add("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("pendingEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("deletedEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
    } else if (button == "deletedEventButton") {
        document
            .getElementById("deletedEventButton")
            .classList.add("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("pendingEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
        document
            .getElementById("completedEventButton")
            .classList.remove("bg-gray-900", "text-green-500", "font-semibold");
    }
}

document.navigateTab = navigateTab;

// Load the pending event as default
let pendingEventButton = document.getElementById("pendingEventButton");

document.addEventListener("DOMContentLoaded", () => {
    pendingEventButton.classList.add(
        "bg-gray-900",
        "text-green-500",
        "font-semibold"
    );
});
