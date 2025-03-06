<?php
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