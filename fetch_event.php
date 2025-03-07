<?php
$conn = new mysqli("localhost", "root", "", "calendar");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];

    // Convert DD-MM-YYYY to YYYY-MM-DD for MySQL
    $date_parts = explode("-", $date);
    $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

    // Fetch event from database
    $query = $conn->prepare("SELECT event_text FROM events WHERE event_date = ?");
    $query->bind_param("s", $formatted_date);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["event" => $row['event_text']]);
    } else {
        echo json_encode(["event" => ""]); // No event found
    }
}
?>
