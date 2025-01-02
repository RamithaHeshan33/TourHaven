<?php
require '../../conn.php';

// Get JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
$guider_id = $conn->real_escape_string($data['guider_id']);
$tourist_email = $conn->real_escape_string($data['tourist_email']);
$guider_name = $conn->real_escape_string($data['guider_name']);
$guider_email = $conn->real_escape_string($data['guider_email']);
$rating = (int)$data['rating'];
$comments = $conn->real_escape_string($data['comments']);

// Insert into the database
$sql = "INSERT INTO ratings (trip_id, tourist_email, guider_name, guider_email, rating, comments)
        VALUES ('$guider_id', '$tourist_email', '$guider_name', '$guider_email', '$rating', '$comments')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Rating submitted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to submit rating']);
}

$conn->close();
?>
