<?php
session_start();
require('../../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "Trip ID is required."]);
        exit;
    }

    $query = "DELETE FROM emergency WHERE id = ?";
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
