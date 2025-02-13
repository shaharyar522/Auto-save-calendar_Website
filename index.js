
// calendar

let currentDate = new Date();

function renderCalendar() {
  const monthYear = document.getElementById("monthYear");
  const calendarDays = document.getElementById("calendarDays");
  const weekDays = document.querySelectorAll(".weekday"); // Select weekday headers (e.g., Sunday, Monday, etc.)

  calendarDays.innerHTML = ""; // Clear previous calendar

  monthYear.innerText = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

  let firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
  let lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
  let today = new Date();
  let todayDayIndex = today.getDay(); // Get index of today's weekday (0 = Sunday, 6 = Saturday)

  // Reset weekday highlights
  weekDays.forEach(day => day.classList.remove("highlighted-day"));

  // If today is in the displayed month, highlight its weekday
  if (currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
    weekDays[todayDayIndex].classList.add("highlighted-day");
  }

  // Create empty cells for alignment
  for (let i = 0; i < firstDay; i++) {
    let emptyCell = document.createElement("div");
    emptyCell.classList.add("day");
    calendarDays.appendChild(emptyCell);
  }

  // Generate day cells for each day in the month
  for (let i = 1; i <= lastDate; i++) {
    let dayCell = document.createElement("div");
    dayCell.classList.add("day");
    dayCell.innerText = i;

    // Highlight the current day if it's today's date
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



// bar chart

document.addEventListener("DOMContentLoaded", function () {
  // Bar Chart
  const ctxBar = document.getElementById('barChart').getContext('2d');

  function getPadding() {
    return {
      left: window.innerWidth < 768 ? 10 : 180,  // Adjust left padding based on screen size
      right: window.innerWidth < 768 ? 10 : 40   // Adjust right padding dynamically
    };
  }

  const barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: ['Em atraso', 'A terminar', 'Concluído'],
      datasets: [{
        label: 'Tarefas',
        data: [10, 5, 18],
        backgroundColor: ['#1CC48F', '#23B5A8', '#254EDB'],
        barThickness: 60,
        maxBarThickness: 80,
        categoryPercentage: 0.9,
        barPercentage: 0.9,
        borderRadius: 5,
      }]
    },

    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: true,
          position: 'left',
          labels: {
            usePointStyle: false,
            boxWidth: 15,
            boxHeight: 15,
            padding: 10,
            font: {
              size: 10,
              weight: 'bold'
            }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { padding: 10 },
          border: { display: false }
        },
        y: {
          grid: { display: false },
          ticks: { display: false },
          border: { display: false }
        }
      },
      layout: {
        padding: getPadding() // Apply dynamic left & right padding
      },
      onResize: function (chart) {
        chart.options.layout.padding = getPadding(); // Update padding on resize
        chart.update();
      }
    }
  });



  // Doughnut Chart
  const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
  new Chart(ctxDoughnut, {
    type: 'doughnut',
    data: {
      labels: ['Open', 'Reopen', 'Hold', 'Monitor', 'Adressed', 'Closed'],
      datasets: [{
        data: [40, 10, 10, 15, 10, 15],
        backgroundColor: ['#254EDB', '#23B5A8', '#1CC48F', '#F7C948', '#E67E22', '#2C3E50']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          position: 'bottom', // Move labels to the bottom
          align: 'center', // Optional: Center align the legend
          labels: {
            usePointStyle: true, // Use squared bullet points instead of circles
            boxWidth: 10, // Adjust box width for square bullets
            padding: 10, // Adjust padding between items

          },

        }
      }
    }
  });

});
// end


// dash board chart
