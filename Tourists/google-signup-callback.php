<?php
session_start();
require '../conn.php';
require '../vendor/autoload.php';

// Google API Client
$client = new Google_Client();
$client->setClientId('274839241301-b7o0dvlceptppe87iv4qk79h2ml7spee.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:3000/Tourists/google-signup-callback.php');

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);

            $oauth = new Google_Service_Oauth2($client);
            $user_info = $oauth->userinfo->get();

            $email = $user_info->email;
            $name = $user_info->name;

            $check_query = "SELECT * FROM tourists WHERE email = ?";
            $stmt = $conn->prepare($check_query);
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $_SESSION['google_name'] = $name;
                $_SESSION['google_email'] = $email;
            
                header("Location: signup.php");
                exit();
            } else {
                $user = $result->fetch_assoc();
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
            
                header("Location: home/home.php");
                exit();
            }
            
        } else {
            header("Location: login.php?message=google_error");
            exit();
        }
    } catch (Exception $e) {
        error_log("Google OAuth Error: " . $e->getMessage());
        header("Location: login.php?message=google_error");
        exit();
    }
} else {
    header("Location: login.php?message=invalid_request");
    exit();
}
?>
