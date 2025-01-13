<?php
session_start();
require '../../conn.php';

if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];

    $email = $_SESSION['email'];
    $sql = "DELETE FROM vehicles WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $vehicle_id, $email);

    if ($stmt->execute()) {
        header("Location: vehicle.php?message=delete");
    } else {
        header("Location: vehicle.php?message=error");
    }
}
?>
