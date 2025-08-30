// Javascript File for Components
// dropdowns, selects, modals, tooltips, etc.

// Dropdown behavior
export function toggleDropdown() {
    const dropdown = document.getElementById("user-menu");
    if (dropdown) {
        dropdown.classList.toggle("hidden");
    }
}

// Only export what's actually needed
export function safeDropdownToggle() {
    // Safe dropdown toggle that checks if element exists
    const dropdown = document.getElementById("user-menu");
    if (dropdown) {
        dropdown.classList.toggle("hidden");
    }
}

// Add the missing function that app.js is trying to import
export function triggerDropdownOnLoad() {
    // This function can be used to initialize dropdowns when the page loads
    // For now, it's a placeholder to fix the import error
    console.log("Dropdown initialization triggered");
}
