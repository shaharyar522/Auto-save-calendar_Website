let currentDate = new Date();

function renderCalendar() {
  const monthYear = document.getElementById("monthYear");
  const calendarDays = document.getElementById("calendarDays");
  calendarDays.innerHTML = "";

  monthYear.innerText = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

  let firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
  let lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
  let today = new Date(); // Get today's actual date

  // Create empty cells for alignment
  for (let i = 0; i < firstDay; i++) {
    let emptyCell = document.createElement("div");
    emptyCell.classList.add("day");
    calendarDays.appendChild(emptyCell);
  }

  // Generate days
  for (let i = 1; i <= lastDate; i++) {
    let dayCell = document.createElement("div");
    dayCell.classList.add("day");
    dayCell.innerText = i;

    // Highlight the current day only if it's in the displayed month
    if (
      i === today.getDate() &&
      currentDate.getMonth() === today.getMonth() &&
      currentDate.getFullYear() === today.getFullYear()
    ) {
      dayCell.classList.add("highlighted");
    }

    calendarDays.appendChild(dayCell);
  }
}

function changeMonth(direction) {
  currentDate.setMonth(currentDate.getMonth() + direction);
  renderCalendar();
}

renderCalendar();
