<?php
$conn = new mysqli("localhost", "root", "", "calendar");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $event = trim($_POST['event']);

    if (!empty($date) && !empty($event)) {
        $date_parts = explode("-", $date);
        $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

        $check_query = $conn->prepare("SELECT * FROM events WHERE event_date = ?");
        $check_query->bind_param("s", $formatted_date);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $query = $conn->prepare("UPDATE events SET event_text = ? WHERE event_date = ?");
            $query->bind_param("ss", $event, $formatted_date);
        } else {
            $query = $conn->prepare("INSERT INTO events (event_date, event_text) VALUES (?, ?)");
            $query->bind_param("ss", $formatted_date, $event);
        }

        if ($query->execute()) {
            echo json_encode(["status" => "success", "message" => "Event saved successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save event!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Event text cannot be empty!"]);
    }
}
?>
