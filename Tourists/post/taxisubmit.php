<?php
    session_start();
    require('../../conn.php');
    
    $name = $_GET['name'] ?? null;
    $tourmail = $_GET['email'] ?? null;
    $team_number = $_GET['team_number'] ?? null;
    $phone = $_GET['phone'] ?? null;
    $address = $_GET['address'] ?? null;
    $destination = $_GET['destination'] ?? null;
    $vehicle_type = $_GET['vehicle_type'] ?? null;
    
    // Validate inputs
    if (!$name || !$tourmail || !$team_number || !$phone || !$address || !$destination || !$vehicle_type) {
        echo "All fields are required!";
        exit();
    }
    
    $sql = "INSERT INTO emergency (name, tourist_mail, team_number, phone, address, destination, vehicle_type)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param("ssissss", $name, $tourmail, $team_number, $phone, $address, $destination, $vehicle_type);
    
    if ($stmt->execute()) {
        header('Location: taxipost.php?message=success');
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
    
?>
