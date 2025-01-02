<?php
    session_start();
    require('../../conn.php');

    $name = $_POST['name'];
    $tourmail = $_POST['email'];
    $team_number = $_POST['team_number'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $destination = $_POST['destination'];
    $st_date = $_POST['st_date'];
    $end_date = $_POST['end_date'];
    $remakes = $_POST['remakes'];

    $sql = "INSERT INTO trip_details (name, tourist_mail, team_number, phone, address, destination, st_date, end_date, remakes)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssissssss", $name, $tourmail, $team_number, $phone, $address, $destination, $st_date, $end_date, $remakes);

    if ($stmt->execute()) {
        header('Location: post.php?message=success');
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
?>
