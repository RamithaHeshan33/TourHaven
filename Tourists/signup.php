<?php
session_start();

// Check if Google session variables are set
$name = isset($_SESSION['google_name']) ? $_SESSION['google_name'] : '';
$email = isset($_SESSION['google_email']) ? $_SESSION['google_email'] : '';
$phone = isset($_SESSION['google_phone']) ? $_SESSION['google_phone'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TourHaven</title>
</head>
<body>
    <h2>Complete Your Signup</h2>
    <form action="signupdata.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly><br><br>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter phone number" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Set a password" required><br><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
