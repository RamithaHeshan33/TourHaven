<?php
require '../../conn.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$jobId = $data['jobId'];
$guider_email = $_SESSION['email'];

if ($jobId && $guider_email) {
    $sql = "UPDATE trip_details SET guider_mail = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $guider_email, $jobId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}
$conn->close();
?>
