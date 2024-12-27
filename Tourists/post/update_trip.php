<?php
session_start();
require('../../conn.php');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $team_number = $_POST['team_number'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $destination = $_POST['destination'];
    $st_date = $_POST['st_date'];
    $end_date = $_POST['end_date'];
    $remarks = $_POST['remarks'];

    // Validate required fields
    if (empty($id) || empty($name) || empty($team_number) || empty($phone) || empty($address) || empty($destination) || empty($st_date) || empty($end_date)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Update query
    $query = "UPDATE trip_details SET name = ?, team_number = ?, phone = ?, address = ?, destination = ?, st_date = ?, end_date = ?, remakes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssi", $name, $team_number, $phone, $address, $destination, $st_date, $end_date, $remarks, $id);

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
