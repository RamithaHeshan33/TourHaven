<?php
session_start();
require('nav/nav.php');
require('conn.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/find.css">
</head>

<body>
    <div class="top">
            <div class="topic">
                <h1 class="first">Expert Car Repair Services,</h1>
                <h1>Wherever You are?</h1>
                <h1>We're Here to Help</h1>


            <a href="Tourists/post/post.php" class="button">Find</a>
            <a href="breakdown_details.php" class="button">Breakdowns</a>

            </div>
            <div class="side-img">
            <img src="../res/about2.jpg" alt="mechanic image">
            </div>
        </div>
</body>
</html>
