<?php
    $server = "localhost:3307";
    $user = "root";
    $password = "";
    $database = "tourhaven";

    $conn = new mysqli($server, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection Failed!! - " . $conn->connect_error);
    }
?>