console.log("Events JS");

// Helper function to safely get element and check if it exists
function getElementSafely(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`Element with id '${id}' not found`);
        return null;
    }
    return element;
}

// Helper function to safely toggle class
function toggleClassSafely(element, className) {
    if (element && element.classList) {
        element.classList.toggle(className);
    }
}

// Helper function to safely add/remove classes
function updateClassesSafely(element, action, ...classes) {
    if (element && element.classList) {
        if (action === "add") {
            element.classList.add(...classes);
        } else if (action === "remove") {
            element.classList.remove(...classes);
        }
    }
}

function closeEventModal() {
    document.querySelector("#EditEventModal").style.display = "none";
}

// Edit Event Function
function editEvent(data) {
    console.log("=== Edit Event Function Called ===");
    console.log("Event data:", data);
    console.log("Event data type:", typeof data);
    console.log("Event data keys:", Object.keys(data));
    console.log("Event ID:", data.id);

    // Check if data is a string (JSON) that needs parsing
    if (typeof data === "string") {
        try {
            data = JSON.parse(data);
            console.log("Parsed event data:", data);
            console.log("Parsed event ID:", data.id);
        } catch (e) {
            console.error("Failed to parse event data:", e);
            return;
        }
    }

    // Validate that we have an ID
    if (!data || !data.id) {
        console.error("Invalid event data or missing ID:", data);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Invalid event data. Please try again.",
        });
        return;
    }

    // Try to open the edit modal
    let modalOpened = false;

    // Approach 1: Try to find Alpine.js instance on the container
    const modalContainer = document.querySelector('[x-data="{ open: false }"]');
    if (modalContainer && modalContainer.__x) {
        try {
            modalContainer.__x.$data.open = true;
            modalOpened = true;
            console.log("Modal opened via Alpine.js __x data");
        } catch (e) {
            console.log("Alpine.js __x access failed:", e);
        }
    }

    // Approach 2: Direct DOM manipulation as fallback
    if (!modalOpened) {
        const modal = document.querySelector(
            ".fixed.inset-0.bg-black.bg-opacity-50",
        );
        if (modal) {
            modal.style.display = "flex";
            modal.classList.remove("hidden");
            modalOpened = true;
            console.log("Modal opened via direct DOM manipulation");
        }
    }

    if (!modalOpened) {
        console.error("Failed to open edit modal");
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to open edit modal. Please try again.",
        });
        return;
    }

    // Populate form fields
    const elements = {
        eventId: document.getElementById("evn_id"),
        eventName: document.getElementById("evn_name"),
        eventDate: document.getElementById("evn_date"),
        eventFines: document.getElementById("evn_fines"),
        checkInStart: document.getElementById("in_start"),
        checkInEnd: document.getElementById("in_end"),
        checkOutStart: document.getElementById("out_start"),
        checkOutEnd: document.getElementById("out_end"),
        afternoonOutEnd: document.getElementById("afternoon_out_end"),
        afternoonOutStart: document.getElementById("afternoon_out_start"),
        afternoonInStart: document.getElementById("afternoon_in_start"),
        afternoonInEnd: document.getElementById("afternoon_in_end"),
        isWholeDay: document.getElementById("isWholeDay"),
    };

    console.log("Form elements found:", elements);

    // Set values safely
    if (elements.eventId) {
        elements.eventId.value = data.id;
        console.log("Set event ID to:", data.id);
    }
    if (elements.eventName) {
        elements.eventName.value = data.event_name;
        console.log("Set event name to:", data.event_name);
    }
    if (elements.eventDate) {
        elements.eventDate.value = data.date;
        console.log("Set event date to:", data.date);
    }
    if (elements.eventFines) {
        elements.eventFines.value = data.fines_amount || "";
        console.log("Set event fines to:", data.fines_amount || "");
    }
    if (elements.checkInStart) {
        elements.checkInStart.value = data.checkIn_start;
        console.log("Set check-in start to:", data.checkIn_start);
    }
    if (elements.checkInEnd) {
        elements.checkInEnd.value = data.checkIn_end;
        console.log("Set check-in end to:", data.checkIn_end);
    }
    if (elements.checkOutStart) {
        elements.checkOutStart.value = data.checkOut_start;
        console.log("Set check-out start to:", data.checkOut_start);
    }
    if (elements.checkOutEnd) {
        elements.checkOutEnd.value = data.checkOut_end;
        console.log("Set check-out end to:", data.checkOut_end);
    }

    // Set afternoon values if elements exist
    if (elements.afternoonOutEnd) {
        elements.afternoonOutEnd.value = data.afternoon_checkOut_end || "";
        console.log(
            "Set afternoon check-out end to:",
            data.afternoon_checkOut_end || "",
        );
    }
    if (elements.afternoonOutStart) {
        elements.afternoonOutStart.value = data.afternoon_checkOut_start || "";
        console.log(
            "Set afternoon check-out start to:",
            data.afternoon_checkOut_start || "",
        );
    }
    if (elements.afternoonInStart) {
        elements.afternoonInStart.value = data.afternoon_checkIn_start || "";
        console.log(
            "Set afternoon check-in start to:",
            data.afternoon_checkIn_start || "",
        );
    }
    if (elements.afternoonInEnd) {
        elements.afternoonInEnd.value = data.afternoon_checkIn_end || "";
        console.log(
            "Set afternoon check-in end to:",
            data.afternoon_checkIn_end || "",
        );
    }

    // Handle whole day checkbox
    if (elements.isWholeDay) {
        if (data.isWholeDay === "true") {
            elements.isWholeDay.checked = true;
            console.log("Set whole day to true");
            const afternoon = document.querySelector("#afternoon_attendance");
            if (afternoon) {
                afternoon.classList.remove("hidden");
                console.log("Showed afternoon attendance section");
            }
        } else {
            elements.isWholeDay.checked = false;
            console.log("Set whole day to false");
            const afternoon = document.querySelector("#afternoon_attendance");
            if (afternoon) {
                afternoon.classList.add("hidden");
                console.log("Hidden afternoon attendance section");
            }
        }
    }

    console.log("Edit modal populated successfully");
}

// Delete Event Function
function deleteEvent(data) {
    console.log("=== Delete Event Function Called ===");
    console.log("Event data:", data);
    console.log("Event data type:", typeof data);
    console.log("Event data keys:", Object.keys(data));
    console.log("Event ID:", data.id);
    console.log("Event ID type:", typeof data.id);
    console.log("Event ID value:", data.id);

    // Check if data is a string (JSON) that needs parsing
    if (typeof data === "string") {
        try {
            data = JSON.parse(data);
            console.log("Parsed event data:", data);
            console.log("Parsed event ID:", data.id);
        } catch (e) {
            console.error("Failed to parse event data:", e);
            return;
        }
    }

    // Validate that we have an ID
    if (!data || !data.id) {
        console.error("Invalid event data or missing ID:", data);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Invalid event data. Please try again.",
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: `You are about to delete the event: ${data.event_name} on ${data.date}. This action cannot be undone.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("User confirmed deletion");

            // Set the event ID in the hidden form
            const deleteForm = document.getElementById("deleteEvent");
            const eventIdInput = document.getElementById("delete_event_id");

            console.log("Delete form found:", deleteForm);
            console.log("Event ID input found:", eventIdInput);

            if (deleteForm && eventIdInput) {
                console.log("Setting event ID to:", data.id);
                eventIdInput.value = data.id;
                console.log(
                    "Form data before submit:",
                    new FormData(deleteForm),
                );

                // Verify the value was set
                if (eventIdInput.value != data.id) {
                    console.error(
                        "Failed to set event ID. Expected:",
                        data.id,
                        "Got:",
                        eventIdInput.value,
                    );
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Failed to set event ID. Please try again.",
                    });
                    return;
                }

                console.log(
                    "Event ID successfully set to:",
                    eventIdInput.value,
                );

                // Submit the form programmatically
                try {
                    console.log("Submitting form with ID:", eventIdInput.value);

                    // Final check to ensure ID is set
                    if (
                        !eventIdInput.value ||
                        eventIdInput.value.trim() === ""
                    ) {
                        console.error("ID field is empty before submission!");
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Event ID is missing. Please try again.",
                        });
                        return;
                    }

                    deleteForm.requestSubmit();
                } catch (e) {
                    console.log(
                        "requestSubmit not supported, using submit():",
                        e,
                    );

                    // Final check to ensure ID is set
                    if (
                        !eventIdInput.value ||
                        eventIdInput.value.trim() === ""
                    ) {
                        console.error("ID field is empty before submission!");
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Event ID is missing. Please try again.",
                        });
                        return;
                    }

                    deleteForm.submit();
                }

                // Show success message
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Event Deleted Successfully",
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else {
                console.error("Delete form elements not found");
                console.error("Delete form:", deleteForm);
                console.error("Event ID input:", eventIdInput);
            }
        } else {
            console.log("User cancelled deletion");
        }
    });
}

// FOR MODAL EVENT WHOLE DAY - with safe element access
function initializeEventHandlers() {
    const afternoon = document.querySelector("#afternoon_attendance");
    const wholeDay = document.querySelector("#wholeDay");
    const isWholeDay = document.querySelector("#isWholeDay");
    const createAfternoonAttendance = document.querySelector(
        "#create_afternoon_attendance",
    );

    // Add event listeners only if elements exist
    if (wholeDay && createAfternoonAttendance) {
        wholeDay.addEventListener("change", function () {
            toggleClassSafely(createAfternoonAttendance, "hidden");
        });
    }

    if (isWholeDay && afternoon) {
        isWholeDay.addEventListener("change", function () {
            toggleClassSafely(afternoon, "hidden");
        });
    }
}

function navigateTab(table, button) {
    console.log(`Navigating to tab: ${table}, button: ${button}`);

    // Define table IDs
    const tableIds = [
        "pendingEventTable",
        "completedEventTable",
        "deletedEventTable",
    ];
    const buttonIds = [
        "pendingEventButton",
        "completedEventButton",
        "deletedEventButton",
    ];

    // Hide all tables first
    tableIds.forEach((tableId) => {
        const tableElement = getElementSafely(tableId);
        if (tableElement) {
            updateClassesSafely(tableElement, "add", "hidden");
        }
    });

    // Show the selected table
    const selectedTable = getElementSafely(table);
    if (selectedTable) {
        updateClassesSafely(selectedTable, "remove", "hidden");
    }

    // Reset all button styles
    buttonIds.forEach((buttonId) => {
        const buttonElement = getElementSafely(buttonId);
        if (buttonElement) {
            updateClassesSafely(
                buttonElement,
                "remove",
                "bg-gray-900",
                "text-green-500",
                "font-semibold",
            );
        }
    });

    // Apply active style to selected button
    const selectedButton = getElementSafely(button);
    if (selectedButton) {
        updateClassesSafely(
            selectedButton,
            "add",
            "bg-gray-900",
            "text-green-500",
            "font-semibold",
        );
    }
}

// Make functions available globally for HTML onclick handlers
if (typeof window !== "undefined") {
    window.editEvent = editEvent;
    window.deleteEvent = deleteEvent;
    window.navigateTab = navigateTab;
}

// Initialize event handlers when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    console.log("Events.js DOM loaded");

    // Initialize event handlers
    initializeEventHandlers();

    // Set default active tab
    const pendingEventButton = getElementSafely("pendingEventButton");
    if (pendingEventButton) {
        updateClassesSafely(
            pendingEventButton,
            "add",
            "bg-gray-900",
            "text-green-500",
            "font-semibold",
        );
    } else {
        console.warn("Pending event button not found");
    }
});

// Make deleteEvent available globally
document.deleteEvent = deleteEvent;
window.deleteEvent = deleteEvent;

// Add form submit debugging
document.addEventListener("DOMContentLoaded", function () {
    const deleteForm = document.getElementById("deleteEvent");
    if (deleteForm) {
        deleteForm.addEventListener("submit", function (e) {
            console.log("=== Form Submit Event ===");
            console.log("Form action:", this.action);
            console.log("Form method:", this.method);

            const formData = new FormData(this);
            console.log("Form data entries:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Check if ID is set
            const idInput = document.getElementById("delete_event_id");
            console.log(
                "ID input value:",
                idInput ? idInput.value : "ID input not found",
            );

            if (!idInput || !idInput.value) {
                console.error("ID field is empty or not found!");
                e.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Event ID is missing. Please try again.",
                });
                return false;
            }

            console.log("Form submission proceeding...");
        });
    } else {
        console.error("Delete form not found on page load");
    }
});

// Make editEvent available globally
document.editEvent = editEvent;
window.editEvent = editEvent;

// Handle whole day checkbox change
function handleWholeDayChange(checkbox) {
    const afternoonSection = document.querySelector("#afternoon_attendance");
    if (afternoonSection) {
        if (!checkbox.checked) {
            afternoonSection.classList.remove("hidden");
            console.log("Showed afternoon attendance section");
        } else {
            afternoonSection.classList.add("hidden");
            console.log("Hidden afternoon attendance section");
        }
    }
}

// Make handleWholeDayChange available globally
document.handleWholeDayChange = handleWholeDayChange;
window.handleWholeDayChange = handleWholeDayChange;

// Handle create event whole day checkbox change
function handleCreateWholeDayChange(checkbox) {
    const afternoonSection = document.querySelector(
        "#create_afternoon_attendance",
    );
    console.log(checkbox.checked);

    if (afternoonSection) {
        if (!checkbox.checked) {
            afternoonSection.classList.remove("hidden");
            console.log("Showed create afternoon attendance section");
        } else {
            afternoonSection.classList.add("hidden");
            console.log("Hidden create afternoon attendance section");
        }
    }
}

// Make handleCreateWholeDayChange available globally
document.handleCreateWholeDayChange = handleCreateWholeDayChange;
window.handleCreateWholeDayChange = handleCreateWholeDayChange;
document.closeEventModal = closeEventModal;
