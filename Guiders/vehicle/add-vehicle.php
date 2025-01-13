<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
}

require '../../conn.php';

$uploadDir = '../../uploads/vehicles/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $vehicleName = $_POST['vehicle_name'];
    $price = $_POST['price'];
    $vehicleType = $_POST['vehicle_type'];
    $description = $_POST['description'];
    
    if (isset($_FILES['main-image']) && $_FILES['main-image']['error'] === UPLOAD_ERR_OK) {
        $mainImage = $_FILES['main-image'];
        $mainImagePath = $uploadDir . uniqid() . '-' . basename($mainImage['name']);

        if (in_array($mainImage['type'], ['image/jpeg', 'image/png', 'image/gif']) && $mainImage['size'] <= 2 * 1024 * 1024) {
            if (!move_uploaded_file($mainImage['tmp_name'], $mainImagePath)) {
                die("Failed to upload primary image.");
            }
        } else {
            die("Invalid primary image file type or size.");
        }
    } else {
        die("Primary image is required.");
    }

    // Save to database
    $sql = "INSERT INTO vehicles (email, vehicle_name, vehicle_type, description, main_image, price) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('ssssss', $email, $vehicleName, $vehicleType, $description, $mainImagePath, $price);

    if ($stmt->execute()) {
        header("location: vehicle.php?message=success");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Invalid request method.");
}
