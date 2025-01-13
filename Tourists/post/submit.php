<?php
    session_start();
    require('../../conn.php');
    
    $name = $_GET['name'] ?? null;
    $tourmail = $_GET['email'] ?? null;
    $team_number = $_GET['team_number'] ?? null;
    $phone = $_GET['phone'] ?? null;
    $address = $_GET['address'] ?? null;
    $destination = $_GET['destination'] ?? null;
    $st_date = $_GET['st_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
    $remakes = $_GET['remakes'] ?? null;
    $vehicle_id = $_GET['vehicle_id'] ?? null;
    $guider_email = $_GET['guider_email'] ?? null;
    
    // Validate inputs
    if (!$name || !$tourmail || !$team_number || !$phone || !$address || !$destination || !$st_date || !$end_date || !$vehicle_id || !$guider_email) {
        echo "All fields are required!";
        exit();
    }
    
    $sql = "INSERT INTO trip_details (name, tourist_mail, guider_mail, team_number, phone, address, destination, st_date, end_date, remakes, vehicle_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param("sssissssssi", $name, $tourmail, $guider_email, $team_number, $phone, $address, $destination, $st_date, $end_date, $remakes, $vehicle_id);
    
    if ($stmt->execute()) {
        header('Location: post.php?message=success');
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
    
?>
