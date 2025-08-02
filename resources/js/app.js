import Alpine from "alpinejs";
import "flowbite"; //I restored Flowbite kay wala nigana ang Dropdowns na gikan Flowbite
import Swal from "sweetalert2"; //Added Sweet Alert module
import "./bootstrap";
import { toggleDropdown, triggerDropdownOnLoad } from "./component";

console.log("Testing App------- Developer");

// AlpineJS
window.Alpine = Alpine;

// Add global Alpine.js initialization to prevent modal issues
Alpine.data('globalModal', () => ({
    init() {
        // Ensure all modals start in closed state
        this.$nextTick(() => {
            // Add alpine-ready class to prevent flashing
            this.$el.classList.add('alpine-ready');
        });
    }
}));

Alpine.start();

// ComponentJS functions
window.toggleDropdown = toggleDropdown;
window.triggerDropdownOnLoad = triggerDropdownOnLoad;
//Clockdate function

//Sweet AlertJS
window.Swal = Swal;
