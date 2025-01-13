<?php
session_start();
require '../../conn.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_name = $_POST['vehicle_name'];
    $vehicle_type = $_POST['vehicle_type'];
    $description = $_POST['description'];
    $main_image = $_FILES['main_image'];
    $price = $_POST['price'];

    if ($main_image['error'] == UPLOAD_ERR_OK) {
        // upload directory
        $upload_dir = '../../uploads/vehicles/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '-' . basename($main_image['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($main_image['tmp_name'], $target_file)) {
            $main_image_path = $target_file;

            $sql = "UPDATE vehicles 
                    SET vehicle_name = ?, vehicle_type = ?, description = ?, main_image = ?, price = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssii', $vehicle_name, $vehicle_type, $description, $main_image_path, $price , $vehicle_id);
        } else {
            echo "Error uploading the file.";
            exit;
        }
    } else {
        $sql = "UPDATE vehicles 
                SET vehicle_name = ?, vehicle_type = ?, description = ?, price = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssii', $vehicle_name, $vehicle_type, $description, $price , $vehicle_id);
    }

    if ($stmt->execute()) {
        echo "Vehicle updated successfully.";
        header("Location: vehicle.php?message=update");
        exit;
    } else {
        echo "Error updating vehicle: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
