<?php
$conn = new mysqli("localhost", "root", "", "calendar");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $event = trim($_POST['event']); // Trim to remove extra spaces

    if (!empty($date) && !empty($event)) {
        // Check if an event already exists for this date
        $stmt = $conn->prepare("SELECT id FROM events WHERE event_date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update the existing event
            $query = $conn->prepare("UPDATE events SET event_text = ? WHERE event_date = ?");
            $query->bind_param("ss", $event, $date);
        } else {
            // Insert new event
            $query = $conn->prepare("INSERT INTO events (event_date, event_text) VALUES (?, ?)");
            $query->bind_param("ss", $date, $event);
        }

        if ($query->execute()) {
            echo json_encode(["status" => "success", "message" => "Event saved successfully!", "event" => $event, "date" => $date]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save event!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Event text cannot be empty!"]);
    }
    exit();
}
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=yes, maximum-scale=5, minimum-scale=1">



  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- custom css -->
  <link rel="stylesheet" href="assets/style.css">

  <!-- fontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <!-- media Queries css-->
  <link rel="stylesheet" href="assets/media-queries.css">


  <title>Recuperar</title>
</head>

<body>


  <!-- navbar -->
  <nav class="navbar navbar-expand-lg ">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand" href="#">
        <img src="./assets/images/logo.png" alt="Logo">
      </a>

      <!-- Toggle button for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon" style="color: #0091DA;"></span>
      </button>

      <!-- Navbar Links + Right Side Items (inside the collapse) -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-5 p-5 mb-2 mb-lg-0 w-100">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Necessidade</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contract_management.html">Gestão de Contrato</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Orçamento</a>
          </li>
        </ul>

        <!-- Right Side Items (Move inside the menu on mobile) -->
        <div class="d-flex align-items-center right-items position-relative">
          <div class="position-relative">
            <i class="fa-regular fa-bell me-2 mt-1 bell-icon" style="color: white; font-size: 22px;"></i>
            <span class="notification-badge">3</span> <!-- Notification Badge -->
          </div>
          <img src="./assets/images/Ellipse 6.png" alt="Profile" class="profile-img mx-2">
          <div class="dropdown">
            <button class="btn btn-outline-light lang-toggle" type="button" data-bs-toggle="dropdown">
              <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" width="20"> PT
              <i class="fa-solid fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">English</a></li>
              <li><a class="dropdown-item" href="#">Português</a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </nav>



  <!-- hero -->
  <div class="hero" style="height: 960px">
    <div class="overlay"></div>
    <div class="container position-relative">
      <h1 class="fw-bold" style="display: inline-block; position: relative; top: 210px; font-size: 50px;">ENTIDADE A
      </h1>
      <p class="d-block" style="position: relative; top: 210px; font-size: 32px;">Entidade A.</p>
    </div>

    <!-- start calendar  -->

    <div class="calendar-card">
      <div class="calendar-header">
        <small class="mb-3 d-block" style="color: #2A6B2F;">Calendário</small>
        <div class="nav-icons d-flex justify-content-between">
          <i class="fa-solid fa-chevron-left" onclick="changeMonth(-1)"></i>
          <small id="monthYear"> <!--  data come dynamically--> </small>
          <i class="fa-solid fa-chevron-right" onclick="changeMonth(1)"></i>
        </div>
      </div>
      <div class="day-names">
        <div class="weekday">Sun</div>
        <div class="weekday">Mon</div>
        <div class="weekday">Tue</div>
        <div class="weekday">Wed</div>
        <div class="weekday">Thu</div>
        <div class="weekday">Fri</div>
        <div class="weekday">Sat</div>

      </div>
      <div class="calendar-days" id="calendarDays"></div>
      <div class="event-box" id="eventDetails">
        <strong id="selectedDate">No Date Selected</strong><br>
        <textarea id="eventText" class="form-control mt-2" placeholder="Enter event details..."></textarea>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
   $(document).ready(function () {
    $(".calendar-days").on("click", ".day", function () {
        let selectedDate = $(this).data("date");
        $("#selectedDate").text(selectedDate);
        $("#eventText").val("");

        $.post("index.php", { date: selectedDate }, function (response) {
            let data = JSON.parse(response);
            $("#eventText").val(data.event);
        });
    });

    $("#eventText").blur(function () {  // Trigger on click away
        let eventText = $(this).val();
        let selectedDate = $("#selectedDate").text();

        if (selectedDate && eventText.trim() !== "") {
            $.post("index.php", { date: selectedDate, event: eventText }, function (response) {
                let data = JSON.parse(response);
                Swal.fire({
                    icon: data.status === "success" ? "success" : "error",
                    title: data.message
                });
            });
        }
    });
});

  </script>
<script >
// uay hmaray pass  jab clendar par clik kartian hain tu us ka background color blue hn  jata hina 

// wo js code hian 

document.addEventListener("DOMContentLoaded", function () {
    const calendarDays = document.getElementById("calendarDays");
    const selectedDateText = document.getElementById("selectedDate");
    const eventText = document.getElementById("eventText");
    const monthYearText = document.getElementById("monthYear");
    let selectedDate = null;
    let currentDate = new Date();

    function generateCalendar(year, month) {
        calendarDays.innerHTML = "";
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        monthYearText.textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${year}`;
        
        for (let i = 0; i < firstDay; i++) {
            let emptyDiv = document.createElement("div");
            emptyDiv.classList.add("empty");
            calendarDays.appendChild(emptyDiv);
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            let dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            dayDiv.textContent = day;
            dayDiv.dataset.date = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

            dayDiv.addEventListener("click", function () {
                document.querySelectorAll(".day").forEach(d => {
                    d.classList.remove("selected");
                    d.style.backgroundColor = "";
                });

                this.classList.add("selected");
                this.style.backgroundColor = "blue";
                selectedDate = this.dataset.date;
                selectedDateText.textContent = selectedDate;

                $.ajax({
                    url: "fetch_event.php",
                    type: "POST",
                    data: { date: selectedDate },
                    success: function (response) {
                        eventText.value = response.trim();
                    }
                });
            });

            calendarDays.appendChild(dayDiv);
        }
    }

    function changeMonth(step) {
        currentDate.setMonth(currentDate.getMonth() + step);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    }

    document.querySelector(".fa-chevron-left").addEventListener("click", function () {
        changeMonth(-1);
    });

    document.querySelector(".fa-chevron-right").addEventListener("click", function () {
        changeMonth(1);
    });

    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

    eventText.addEventListener("blur", function () {
        if (selectedDate && eventText.value.trim() !== "") {
            $.ajax({
                url: "save_event.php",
                type: "POST",
                data: { date: selectedDate, event: eventText.value },
                success: function (response) {
                    Swal.fire("Success", "Your data has been saved!", "success");
                }
            });
        }
    });
});
</script>

  <!-- End calendar  -->




  <!-- bar chart section -->

  <section class="barchartSection mb-3">
    <div class="container py-4">
      <h3 class="fw-bold" style="color: #4E4E4E !important;">Gráfico de Necessidades</h3>
      <div class="row mt-3">
        <div class="col-md-6">
          <div class="chart-container">
            <h5 class="fw-bold" style="color: #03445E !important; display: block;">Alerta de tarefas</h5>

            <!-- Flexbox for horizontal layout -->
            <div class="mainBar" style="display: flex; align-items: center;">
              <div class="barChart-items me-4">
                <ul class="list-unstyled">
                  <li><span class="badge bg-primary">&nbsp;</span> Concluído</li>
                  <li><span class="badge bg-info">&nbsp;</span> A terminar</li>
                  <li><span class="badge bg-success">&nbsp;</span> Em atraso</li>
                </ul>
              </div>
              <div>
                <canvas id="barChart" style="width: 100% !important; height: 300px !important;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 ">
          <div class="chart-container">
            <h5 class="fw-bold" style="display: inline-block; white-space: nowrap; color: #03445E;">Estado das tarefas
            </h5>
            <canvas id="doughnutChart" class="" style=" width: 222px;" height="222px">
              <!-- data come -->
            </canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- dashboard chart  -->
    <div class=" container mb-5 py-5">
      <div class="dashboard-container row">
        <div class="col-md-5">
          <h5 class="fw-bold" style="color: #03445E;">Contratos</h5>
          <div id="bar-chart"></div>
        </div>
        <div class="col-md-7">
          <h5 class="fw-bold" style="color: #03445E;">Contratos <br> (Volume em &euro;)</h5>
          <canvas id="lineChart"></canvas>
        </div>
      </div>
    </div>
  </section>

  <!-- end -->

  <!-- candidate section -->
  <section class="candidate-section p-4">
    <div class="container">
      <div class="row justify-content-center"> <!-- Added row here -->
        <div class="col-12"> <!-- Added column wrapper for better responsiveness -->
          <div class="main p-3" style="  margin-top: 50px;">
            <div class="row">
              <div class="col-md-6">
                <h3 class="fw-bold mt-4 ms-2" style="color: #4E4E4E;">Necessidades</h3>
              </div>
              <div class="col-md-6">
                <div class="candidate-btn mt-4 me-3 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary candidate-btn1">Criar Necessidade</button>
                  <button type="button" class="btn btn-outline-light ms-1 candidate-serchBtn">
                    <i class="fa-solid fa-magnifying-glass"></i> Search ID Task
                  </button>
                </div>
              </div>
            </div>
            <!-- table -->
            <div class="row">
              <div class="table-container table-responsive needs-table">
                <div class="d-flex align-items-center mb-3 table-date">
                  <i class="fa-solid fa-chevron-left me-3" id="table-icons"></i>
                  <span style="color: #3F8045;"><i class="fa-solid fa-calendar-days"></i> &nbsp;Janeiro 2024</span>
                  <i class="fa-solid fa-chevron-right ms-3 table-icons"></i>
                </div>
                <table class="table table-hover text-center table-responsive">
                  <thead>
                    <tr>
                      <th>Company</th>
                      <th>Service</th>
                      <th>Subject</th>
                      <th>Category</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Assigned To</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>1/19/2024</td>
                      <td>Hold</td>
                      <td>Lorem Ipsum</td>
                    </tr>
                    <tr>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>1/20/2024</td>
                      <td>Hold</td>
                      <td>Lorem Ipsum</td>
                    </tr>
                    <tr>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>1/21/2024</td>
                      <td>&#x2714;</td>
                      <td>Lorem Ipsum</td>
                    </tr>
                    <tr>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>1/22/2024</td>
                      <td>&#x2714;</td>
                      <td>Lorem Ipsum</td>
                    </tr>
                    <tr>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>Lorem Ipsum</td>
                      <td>1/30/2024</td>
                      <td>Hold</td>
                      <td>Lorem Ipsum</td>
                    </tr>
                  </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="mb-0 fw-bold table-para" style="color: #3F8045;">8 - 36 Tarefas</p>
                  <nav>
                    <ul class="pagination">
                      <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item active"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">4</a></li>
                      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div> <!-- End of .main -->
        </div> <!-- End of .col-12 -->
      </div> <!-- End of .row -->
    </div>
  </section>

  <!-- footer section -->

  <footer style="background-color: #172629;">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mt-5">
          <h3 class="fw-bold">contacte-nos
          </h3>
        </div>

        <div class="col-md-8 mt-5">
          <div class="quick-links">
            <a href=""> <span>TERMOS E CONDIÇÕES</span></a>
            <a href=""> <span>POLÍTICA DE PRIVACIDADE</span></a>
            <a href=""> <span>POLÍTICA DE COOKIES</span></a>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <div class="social-links">
              <a href=""> <i class="fa-brands fa-youtube"></i></a>
              <a href=""><i class="fa-brands fa-facebook"></i></a>
              <a href=""> <i class="fa-brands fa-linkedin"></i></a>
              <a href=""> <i class="fa-brands fa-twitter"></i></a>
              <a href=""> <i class="fa-brands fa-instagram"></i></a>

            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>


  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  <!-- custom js -->
  <script src="index.js"></script>
  <!-- chart library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    const barData = [{
        value: 0.25,
        width: "210px",
        height: "50px",
        color: "#0A5462"
      },
      {
        value: 0.20,
        width: "190px",
        height: "50px",
        color: "#235A9E"
      },
      {
        value: 0.10,
        width: "90px",
        height: "50px",
        color: "#00C9A7"
      },
      {
        value: 0.08,
        width: "50px",
        height: "50px",
        color: "#16B46F"
      }
    ];

    const barChartContainer = document.getElementById("bar-chart");

    // Ensure the container has some width
    barChartContainer.style.display = "flex";
    barChartContainer.style.flexDirection = "column";
    barChartContainer.style.gap = "10px";

    barData.forEach(data => {
      const barItem = document.createElement("div");
      barItem.className = "bar-item";
      barItem.innerHTML = `
      <div class="bar" style="
          width: ${data.width}; 
          height: ${data.height}; 
          background-color: ${data.color}; 
          display: flex; 
          align-items: center; 
          justify-content: end; 
          color: white; 
          font-weight: bold;
          font-size: 12px;
          border-radius: 5px;
      ">
          ${data.value} M
      </div>
      <span class="ms-2" style="color:#03445E"; font-size:16px;>Lorem Ipsum</span>
  `;
      barChartContainer.appendChild(barItem);
    });


    const ctx = document.getElementById('lineChart').getContext('2d');

    // Create a linear gradient for the bars
    const gradient = ctx.createLinearGradient(0, 0, 0, 400); // Start at the top (0) and go to the bottom (400)

    // chart gradient background color 
    gradient.addColorStop(0, '#f7f5ee'); // First color stop
    gradient.addColorStop(0.25, '#eff5e8'); // Middle color stop
    gradient.addColorStop(0.40, '#d6f0df'); // Last color stop

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            data: [2.4, 2.4, 0.4, 3, 1.6, 3.5],
            borderColor: '#235A9E',
            backgroundColor: '#fff',
            borderWidth: 2,
            pointBackgroundColor: ['#00C9A7', '#00C9A7', '#00C9A7', '#00C9A7', '#00C9A7', '#fff'],
            pointRadius: [5, 5, 5, 5, 5, 8],
            label: 'Line Dataset', // Ensure every dataset has a label
          },
          {
            type: 'bar',
            data: [null, null, null, null, null, 4],
            backgroundColor: gradient,
            barThickness: 50,
            label: 'Bar Dataset', // Ensure every dataset has a label
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: false
            },
            ticks: {
              display: false, // Remove ticks (numbers)
              drawTicks: false // Hide the ticks (vertical lines)
            },
            border: {
              display: false // Remove the y-axis border line
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  </script>
</body>

</html>