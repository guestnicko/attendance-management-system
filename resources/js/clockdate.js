// Clock-date
// Source code: https://codepen.io/paarmita/pen/YroXwv
// Improvise by ChatGPT
export function startTime() {
    const updateClock = () => {
        try {
            const today = new Date();
            let [hr, min, sec] = [
                today.getHours(),
                today.getMinutes(),
                today.getSeconds(),
            ];

            const ap = hr < 12 ? "<span>AM</span>" : "<span>PM</span>";
            hr = hr % 12 || 12; // Convert 0 to 12 and adjust 24-hour format

            const formatTime = (i) => (i < 10 ? "0" + i : i);
            const timeString = `${formatTime(hr)}:${formatTime(
                min
            )}:${formatTime(sec)} ${ap}`;

            const clockElement = document.getElementById("clock");
            if (clockElement) {
                clockElement.innerHTML = timeString;
            } else {
                console.error("Clock element not found during update");
            }

            const months = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ];
            const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            const dateString = `${days[today.getDay()]}, ${today.getDate()} ${
                months[today.getMonth()]
            } ${today.getFullYear()}`;

            const dateElement = document.getElementById("date");
            if (dateElement) {
                dateElement.innerHTML = dateString;
            } else {
                console.error("Date element not found during update");
            }
        } catch (error) {
            console.error("Error updating clock:", error);
        }
    };

    try {
        updateClock(); // Run immediately
        setInterval(updateClock, 1000); // Update every second
        console.log("Clock interval set successfully");
    } catch (error) {
        console.error("Error setting clock interval:", error);
    }
}
