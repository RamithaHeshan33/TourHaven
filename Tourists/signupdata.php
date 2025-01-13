<?php
session_start();
require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM tourists WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?message=alreadyexit");
        exit;
    }

    else{
        $insert_query = "INSERT INTO tourists (email, name, password, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssss', $email, $name, $password, $phone);

        if ($stmt->execute()) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            unset($_SESSION['google_name']);
            unset($_SESSION['google_email']);
            unset($_SESSION['google_phone']);

            header("Location: login.php?message=sucreg");
            exit();
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }

    
} else {
    echo "Invalid request method.";
}
?>
