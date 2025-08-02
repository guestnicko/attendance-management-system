// Javascript File for Components
// dropdowns, selects, modals, tooltips, etc.

// Dropdown behavior
export function toggleDropdown(){
    const dropdown = document.getElementById("user-menu");
    dropdown.classList.toggle('hidden');
}

//Select Menu from Flowbite
export function triggerDropdownOnLoad() {
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownButton = document.querySelector('[data-dropdown-toggle="dropdown"]');

        if (dropdownButton) {
            // Only trigger if not already open to avoid interference with modals
            if (!dropdownButton.getAttribute('aria-expanded') || dropdownButton.getAttribute('aria-expanded') === 'false') {
                dropdownButton.click();
            }
        } else {
            console.error("Dropdown button not found");
        }
    });
}