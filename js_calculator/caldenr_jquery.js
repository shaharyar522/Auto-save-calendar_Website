
/// uay hamray pass j query ka code jab hum data ente kartain hian tu page refersh nhi hnta

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
