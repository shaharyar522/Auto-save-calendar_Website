<?php
$conn = new mysqli("localhost", "root", "", "calendar");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $event = isset($_POST['event']) ? trim($_POST['event']) : '';

    if (!empty($date)) {
        // Convert date format from DD-MM-YYYY to YYYY-MM-DD
        $date_parts = explode("-", $date);
        $formatted_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];

        if ($event === '') {
            // Delete event if text is empty
            $query = $conn->prepare("DELETE FROM events WHERE event_date = ?");
            $query->bind_param("s", $formatted_date);
        } else {
            // Check if event already exists
            $check_query = $conn->prepare("SELECT id FROM events WHERE event_date = ?");
            $check_query->bind_param("s", $formatted_date);
            $check_query->execute();
            $result = $check_query->get_result();

            if ($result->num_rows > 0) {
                // Update event if it exists
                $query = $conn->prepare("UPDATE events SET event_text = ? WHERE event_date = ?");
                $query->bind_param("ss", $event, $formatted_date);
            } else {
                // Insert new event
                $query = $conn->prepare("INSERT INTO events (event_date, event_text) VALUES (?, ?)");
                $query->bind_param("ss", $formatted_date, $event);
            }
        }

        if ($query->execute()) {
            echo json_encode(["status" => "success", "message" => ($event === '' ? "Event deleted successfully!" : "Event saved successfully!")]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to process request!"]);
        }
    }
}
?>
