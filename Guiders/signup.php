<?php
session_start();

// Fetch Google session variables
$name = isset($_SESSION['google_name']) ? $_SESSION['google_name'] : '';
$email = isset($_SESSION['google_email']) ? $_SESSION['google_email'] : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TourHaven</title>
    <link rel="shortcut icon" href="../res/logo.jpg">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form">
        <h2>Complete Your Signup</h2>
        <?php if ($message == 'alreadyexit'): ?>
            <div class="message" id="success-alert">This email already exists!</div>
        <?php endif; ?>
        <form action="signupdata.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="" placeholder="Enter phone number" required><br><br>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Set a password" required><br><br>
            </div>

            <div class="form-group">
                <label for="city">Living City:</label>
                <input type="text" id="city" name="city" placeholder="Enter living city" required><br><br>
            </div>

            <div class="form-group">
                <button type="submit">Sign Up</button>
            </div>

            <p>Already Registered? Please <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
