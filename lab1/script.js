document.addEventListener("DOMContentLoaded", function () {
    const yearDropdown = document.getElementById("year");
    const monthDropdown = document.getElementById("month");
    const dayDropdown = document.getElementById("day");

    function updateDayDropdown() {
        const selectedYear = parseInt(yearDropdown.value);
        const selectedMonth = parseInt(monthDropdown.value);
        const lastDay = new Date(selectedYear, selectedMonth, 0).getDate();

        // Clear existing options
        dayDropdown.innerHTML = "";

        for (let i = 1; i <= lastDay; i++) {
            const option = document.createElement("option");
            option.text = i;
            dayDropdown.add(option);
        }
    }


    const currentYear = new Date().getFullYear();
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
        const option = document.createElement("option");
        option.text = i;
        yearDropdown.add(option);
    }

    for (let i = 1; i <= 12; i++) {
        const option = document.createElement("option");
        option.text = i;
        monthDropdown.add(option);
    }

    updateDayDropdown();

    yearDropdown.addEventListener("change", updateDayDropdown);
    monthDropdown.addEventListener("change", updateDayDropdown);

    function updateTaskList() {
        const taskListContainer = document.getElementById("task-container");

        const xhr = new XMLHttpRequest();

        xhr.open("GET", "display.php", true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                taskListContainer.innerHTML = xhr.responseText;
            } else {
                console.error("Failed to fetch task list.");
            }
        };

        xhr.send();
    }

    updateTaskList();

    const form = document.querySelector("form");
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const year = yearDropdown.value;
        const month = monthDropdown.value;
        const day = dayDropdown.value;
        const task = document.getElementById("task").value;

        const xhr = new XMLHttpRequest();

        xhr.open("POST", "process.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                updateTaskList();
            } else {
                console.error("Failed to add task.");
            }
        };

        xhr.send(`year=${year}&month=${month}&day=${day}&task=${task}`);

        document.getElementById("task").value = "";
    });
});
