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