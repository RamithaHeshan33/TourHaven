<?php
session_start();
require '../conn.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM guiders WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?message=alreadyexit");
        exit;
    }

    else{
        // Insert the user data into the database
        $insert_query = "INSERT INTO guiders (email, name, password, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssss', $email, $name, $password, $phone);

        if ($stmt->execute()) {
            // Store user data in session and redirect to home.php
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;

            // Clear temporary Google session data
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
