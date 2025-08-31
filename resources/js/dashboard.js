import { startTime as startClockTime } from "./clockdate.js";
import { testStudentForm } from "./students.js";

console.log("Dashboard JS loaded");

// Dashboard JS Functions
window.testStudentForm = testStudentForm;

// Initialize clock and date display
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM Content Loaded - Initializing Dashboard");

    // Start the real-time clock
    const clockElement = document.getElementById("clock");
    const dateElement = document.getElementById("date");

    if (clockElement && dateElement) {
        try {
            startClockTime();
            console.log("Clock initialized successfully");
        } catch (error) {
            console.error("Error initializing clock:", error);
        }
    } else {
        console.log(
            "Clock elements not found - clock:",
            clockElement,
            "date:",
            dateElement
        );
    }

    // Initialize other dashboard functionality
    try {
        initializeDashboard();
        console.log("Dashboard initialized successfully");
    } catch (error) {
        console.error("Error initializing dashboard:", error);
    }
});

// Initialize dashboard components
function initializeDashboard() {
    console.log("Initializing dashboard components...");

    // Initialize fullscreen toggle
    const fullscreenToggle = document.getElementById("fullscreenToggle");
    if (fullscreenToggle) {
        fullscreenToggle.addEventListener("click", function () {
            let tableContainer = document.getElementById("tableContainer");
            if (!document.fullscreenElement) {
                tableContainer.requestFullscreen().catch((err) => {
                    console.error(
                        "Error attempting to enable full-screen mode:",
                        err
                    );
                });
            } else {
                document.exitFullscreen();
            }
        });
        console.log("Fullscreen toggle initialized");
    } else {
        console.log("Fullscreen toggle not found");
    }

    // Initialize whole day event toggle
    const wholeDayCheckbox = document.querySelector("#wholeDay");
    const afternoonAttendance = document.querySelector("#afternoon_attendance");

    if (wholeDayCheckbox && afternoonAttendance) {
        wholeDayCheckbox.addEventListener("change", function () {
            afternoonAttendance.classList.toggle("hidden");
        });
        console.log("Whole day toggle initialized");
    } else {
        console.log("Whole day toggle elements not found");
    }
}

// The code is from dashboard.blade.php sa script
// Pero gi transfer lng nako dire para uniformed, since testing function raman sad
