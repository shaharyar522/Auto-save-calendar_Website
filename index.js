
// calendar

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


// bar chart

document.addEventListener("DOMContentLoaded", function () {
  // Bar Chart
  const ctxBar = document.getElementById('barChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: ['Em atraso', 'A terminar', 'Concluído'],
      datasets: [{
        label: 'Tarefas',
        data: [10, 5, 18],
        backgroundColor: ['#1CC48F', '#23B5A8', '#254EDB'],
        barPercentage: 0.7,
        categoryPercentage: 0.9
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: true,
          position: 'left', // Move legend to the left
          labels: {
            usePointStyle: false, // Disable default bullet points
            boxWidth: 15, // Make the squares bigger
            boxHeight: 15, // Ensure squares are square
            padding: 10, // Add spacing between items
            font: {
              size: 10, // Adjust font size
              weight: 'bold' // Make text bold
            }
          },
        }
      },
      scales: {
        x: {
          grid: {
            display: false
          },
          ticks: {
            padding: 10
          }
        },
        y: {
          grid: {
            display: false
          },
          ticks: {
            display: false
          }
        }
      },
      layout: {
        padding: {
          left: 50
        }
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
