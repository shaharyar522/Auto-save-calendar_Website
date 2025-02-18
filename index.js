
// calendar

let currentDate = new Date();
let selectedDays = [4, 5, 8, 9, 11, 12, 16]; // Example: Pre-selecting the first 3 days

function renderCalendar() {
  const monthYear = document.getElementById("monthYear");
  const calendarDays = document.getElementById("calendarDays");
  const weekDays = document.querySelectorAll(".weekday");

  calendarDays.innerHTML = ""; // Clear previous calendar

  monthYear.innerText = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

  let firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
  let lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
  let today = new Date();
  let todayDayIndex = today.getDay(); // Get index of today's weekday (0 = Sunday, 6 = Saturday)

  // Reset weekday highlights
  weekDays.forEach(day => day.classList.remove("highlighted-day"));

  if (currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
    weekDays[todayDayIndex].classList.add("highlighted-day");
  }

  // Create empty cells for alignment
  for (let i = 0; i < firstDay; i++) {
    let emptyCell = document.createElement("div");
    emptyCell.classList.add("day");
    calendarDays.appendChild(emptyCell);
  }

  let selectedDays = [4, 5, 8, 9, 11, 12, 16]; // Example: Pre-selecting the first 3 days


  const colors = ['#025373', '#025373', '#025373', '#F0E130', '#025373', '#4FA0BF', '#F0E130'];

  for (let i = 1; i <= lastDate; i++) {
    let dayCell = document.createElement("div");
    dayCell.classList.add("day");
    dayCell.innerText = i;


    if (i === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
      dayCell.classList.add("highlighted");
    }


    if (selectedDays.includes(i)) {
      dayCell.classList.add("highlighted");  // Add general highlight for pre-selected days

      const index = selectedDays.indexOf(i);
      dayCell.style.backgroundColor = colors[index]; // Assign background color dynamically
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

  // Function to return dynamic padding values based on screen size
  function getPadding() {
    if (window.innerWidth < 768) {
      return {
        left: 50,
        right: 20
      };
    } else if (window.innerWidth >= 768 && window.innerWidth < 1024) {
      return {
        left: 100, // Increased padding for tablets or medium screens
        right: 20
      };
    } else {
      return {
        left: 90, // Adjust this value for large screens
        right: 30
      };
    }
  }

  const taskValues = [10, 5, 18]; // Integer values to show below labels
  const taskLabels = ['Em atraso', 'A terminar', 'Concluído'];

  // Create the bar chart
  const barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: taskLabels,
      datasets: [{
        label: 'Tarefas',
        data: taskValues,
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
      maintainAspectRatio: false, // Allow resizing the chart
      plugins: {
        legend: {
          display: false
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: {
            padding: 10,
            callback: function (value, index, values) {
              return [taskValues[index], taskLabels[index]]; // Value on top, label below
            },
            font: {
              size: 14,
            }
          },
          border: { display: false }
        },
        y: {
          grid: { display: false },
          ticks: { display: false },
          border: { display: false }
        }
      },
      layout: {
        padding: getPadding()
      },
    }
  });

  // Listen for window resize events to dynamically update chart size
  window.addEventListener('resize', function () {
    // Update padding based on screen size
    barChart.options.layout.padding = getPadding();
    barChart.update(); // Update the chart with new padding and settings
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
      maintainAspectRatio: true, // You can toggle this to false if you want full control over the height/width
      aspectRatio: 1.9, // Adjust the aspect ratio as needed (1 for square, adjust values for other ratios)
      plugins: {
        legend: {
          position: 'bottom',
          align: 'center',
          labels: {
            usePointStyle: true,
            boxWidth: 10,
            padding: 10,
          },
        }
      }
    }
  });



});
// end
