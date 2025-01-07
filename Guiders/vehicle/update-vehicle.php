<?php
session_start();
require '../../conn.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_name = $_POST['vehicle_name'];
    $vehicle_type = $_POST['vehicle_type'];
    $description = $_POST['description'];
    $main_image = $_FILES['main_image'];

    // Check if a new main image is uploaded
    if ($main_image['error'] == UPLOAD_ERR_OK) {
        // Define upload directory
        $upload_dir = '../../uploads/vehicles/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate a unique file name
        $file_name = uniqid() . '-' . basename($main_image['name']);
        $target_file = $upload_dir . $file_name;

        // Move the uploaded file
        if (move_uploaded_file($main_image['tmp_name'], $target_file)) {
            // Image successfully uploaded
            $main_image_path = $target_file;

            // Update the vehicle details with the new image
            $sql = "UPDATE vehicles 
                    SET vehicle_name = ?, vehicle_type = ?, description = ?, main_image = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssi', $vehicle_name, $vehicle_type, $description, $main_image_path, $vehicle_id);
        } else {
            echo "Error uploading the file.";
            exit;
        }
    } else {
        // Update the vehicle details without changing the image
        $sql = "UPDATE vehicles 
                SET vehicle_name = ?, vehicle_type = ?, description = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $vehicle_name, $vehicle_type, $description, $vehicle_id);
    }

    // Execute the query
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
