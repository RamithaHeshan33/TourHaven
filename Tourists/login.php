<?php
    session_start();
    require '../vendor/autoload.php';
    require '../conn.php';

    // Google API Client
    $client = new Google_Client();
    $client->setClientId('274839241301-b7o0dvlceptppe87iv4qk79h2ml7spee.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost:3000/Tourists/google-signup-callback.php');
    $client->addScope('email');
    $client->addScope('profile');

    $google_login_url = $client->createAuthUrl();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $query = $conn->query("SELECT * FROM tourists WHERE email='$email'");
        if ($query->num_rows > 0) {
            $user = $query->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                header('Location: home/home.php');
            } else {
                header("Location: login.php?message=invalid");
                exit;
            }
        } else {
            header("Location: login.php?message=nouser");
            exit;
        }
    }

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
        <h2>Welcome to TourHaven</h2>
            <?php if ($message == 'invalid'): ?>
                <div class="message" id="success-alert">Invalid credentials.</div>
            <?php elseif ($message == 'nouser'): ?>
                <div class="message" id="success-alert">No user found with this email.</div>
            <?php elseif ($message == 'sucreg'): ?>
                <div class="success" id="success-alert">Registration Successful!.</div>
            <?php elseif ($message == 'err'): ?>
                <div class="alert fail-success" id="success-alert">Insufficient Quantity.</div>
            <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>

            <p>New User? Please <a href="signup.php">Register</a> </p>
        </form>

        <hr>

        <a href="<?php echo $google_login_url; ?>">
            <button class="gbtn">
                <img src="../res/google.png" alt="" class="btn-image"> Continue with Google
            </button>
        </a>
    </div>
</body>
</html>
