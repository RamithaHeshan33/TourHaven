<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('location: ../login.php');
    exit;
}

require('../nav.php');
require('../../conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/find.css">
    <title>Find Car</title>
</head>
<body>
    <div class="top">
        <div class="topic">
            <h1 class="first">Do you need a Travel Companion</h1>
            <p>
                Imagine discovering new destinations with a trusted companion by your side. Whether you need someone to share the journey or
                help during a challenging moment, we’re here to make your travel plans stress-free. Let’s connect you with like-minded
                travelers and reliable assistance when you need it most.
            </p>
            <button class="button" onclick="window.location.href='../post/post.php'">Find Guider</button>
            <button class="button" onclick="window.location.href='../post/tripposts.php'">Your Trips</button>
            <button class="button" onclick="window.location.href='../post/taxipost.php'">Find Taxi</button>
            <button class="button" onclick="window.location.href='../post/yourtaxiposts.php'">Your Taxis</button>

        </div>
        <div class="side-img">
            <img src="../../res/about2.jpg" alt="mechanic image">
        </div>
    </div>
</body>
</html>
