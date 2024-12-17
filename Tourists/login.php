<?php
    session_start();
    require '../vendor/autoload.php'; // Include the Composer autoload file

    // Google API Client
    $client = new Google_Client();
    $client->setClientId('274839241301-b7o0dvlceptppe87iv4qk79h2ml7spee.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost:3000/Tourists/google-signup-callback.php');
    $client->addScope('email');
    $client->addScope('profile');

    // Google Login URL
    $google_login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TourHaven - Login</title>
</head>
<body>
    <h2>Welcome to TourHaven</h2>
    <a href="<?php echo $google_login_url; ?>">
        <button style="padding: 10px 20px; background-color: #4285F4; color: white; border: none; cursor: pointer;">
            Login with Google
        </button>
    </a>
</body>
</html>
