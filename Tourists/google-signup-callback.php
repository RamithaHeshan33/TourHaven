<?php
session_start();
require '../conn.php'; // Include database connection
require '../vendor/autoload.php'; // Google Client Library autoload

// Google API Client
$client = new Google_Client();
$clientID = getenv('GOOGLE_OAUTH_CLIENT_ID');
$clientSecret = getenv('GOOGLE_OAUTH_CLIENT_SECRET');
$client->setRedirectUri('http://localhost:3000/Tourists/google-signup-callback.php');

if (isset($_GET['code'])) {
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
        $check_query = "SELECT * FROM tourists WHERE email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // New user: store details in session and redirect to signup.php
            $_SESSION['google_name'] = $name;
            $_SESSION['google_email'] = $email;
            $_SESSION['google_phone'] = ""; // Google doesn't provide phone by default

            header("Location: signup.php");
            exit();
        } else {
            // Existing user: log in and redirect to home.php
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;

            header("Location: home.php");
            exit();
        }
    } else {
        echo "Error fetching token: " . $token['error'];
    }
} else {
    echo "Invalid request. No code parameter found.";
}
?>
