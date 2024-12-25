<?php
session_start(); // Ensure session is started at the top
require '../conn.php';
require '../vendor/autoload.php';

// Google API Client
$client = new Google_Client();
$client->setClientId('274839241301-b7o0dvlceptppe87iv4qk79h2ml7spee.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:3000/Guiders/google-signup-callback.php');

if (isset($_GET['code'])) {
    try {
        // Exchange authorization code for an access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (!isset($token['error'])) {
            // Set access token
            $client->setAccessToken($token['access_token']);

            // Get user information
            $oauth = new Google_Service_Oauth2($client);
            $user_info = $oauth->userinfo->get();

            // Extract user details
            $email = $user_info->email;
            $name = $user_info->name;

            // Check if the user already exists
            $check_query = "SELECT * FROM guiders WHERE email = ?";
            $stmt = $conn->prepare($check_query);
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // New user: store details in session and redirect to signup.php
                $_SESSION['google_name'] = $name; // Use consistent naming
                $_SESSION['google_email'] = $email;
            
                header("Location: signup.php");
                exit();
            } else {
                // Existing user: log them in
                $user = $result->fetch_assoc();
                $_SESSION['user_name'] = $user['name']; // Ensure this matches DB column
                $_SESSION['email'] = $user['email'];
            
                header("Location: home/home.php");
                exit();
            }
        } else {
            // Token error: Redirect to login with an error message
            header("Location: login.php?message=google_error");
            exit();
        }
    } catch (Exception $e) {
        // General error handling
        error_log("Google OAuth Error: " . $e->getMessage());
        header("Location: login.php?message=google_error");
        exit();
    }
} else {
    // Invalid request: Redirect to login
    header("Location: login.php?message=invalid_request");
    exit();
}
?>
