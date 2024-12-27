<?php
session_start();
require('../../conn.php');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the ID from the request
    $id = $_POST['id'];

    // Validate ID
    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "Trip ID is required."]);
        exit;
    }

    // Delete query
    $query = "DELETE FROM trip_details WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Trip deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete trip."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
