<?php
session_start();
require('../../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $team_number = $_POST['team_number'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $destination = $_POST['destination'];

    if (empty($id) || empty($name) || empty($team_number) || empty($phone) || empty($address) || empty($destination)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Update query
    $query = "UPDATE emergency SET name = ?, team_number = ?, phone = ?, address = ?, destination = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $team_number, $phone, $address, $destination, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Trip details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update trip details."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
