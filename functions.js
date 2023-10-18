function updateStatus() {
  var statusElement = document.querySelector('.dstatus');
  var apiUrl = "https://sgp1.blynk.cloud/external/api/isHardwareConnected?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9";

  // Make a GET request
  fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
      if (data === true) {
        statusElement.textContent = "Online";
        statusElement.classList.remove("offline");
        statusElement.classList.add("online");
      } else {
        statusElement.textContent = "Offline";
        statusElement.classList.remove("online");
        statusElement.classList.add("offline");
      }
    })
    .catch(error => {
      console.error("Error fetching data:", error);
      statusElement.textContent = "Offline"; // Set to "Offline" in case of an error
      statusElement.classList.remove("online");
      statusElement.classList.add("offline");
    });
}

// Call the function when the page loads
window.onload = updateStatus;


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

window.addEventListener('load', function () {
  document.body.classList.add('loaded');
});

