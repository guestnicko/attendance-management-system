import axios from "axios";
import { startTime } from "./clockdate.js";
import { toggleDropdown, triggerDropdownOnLoad } from "./component.js";

console.log("Dashboard JS");

//Dashboard JS Functions
window.testStudentForm = testStudentForm;
window.startTime = startTime;
window.toggleDropdown = toggleDropdown;
triggerDropdownOnLoad();
// The code is from dashboard.blade.php sa script
// Pero gi transfer lng nako dire para uniformed, since testing function raman sad
