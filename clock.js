document.addEventListener('DOMContentLoaded', function () {
    function updateClock() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');

        let timeString = `${hours % 12 || 12}:${minutes}:${seconds} ${hours >= 12 ? 'PM' : 'AM'}`;

        document.querySelector('.clock #time').textContent = timeString;
    }

    // Call the updateClock function to display the current time
    setInterval(updateClock, 1000);
    updateClock(); // Call it once to display the time immediately
});
