<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TourHaven</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>You have successfully signed up with your Google Account.</p>
    <a href="../logout.php">Logout</a>
</body>
</html>
