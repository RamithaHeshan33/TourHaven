<?php
session_start();
require '../nav.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: ../signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TourHaven</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <!-- <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>You have successfully signed up with your Google Account.</p>
    <a href="../logout.php">Logout</a> -->

    <video class="video-background" autoplay loop muted>
        <source src="../../res/background1.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="home">
        <div class="content">
            <div class="para">
                <h1>Welcome to TourHaven – Your Travel Companion</h1>
                <p class="items-justify">
                    TourHaven is a comprehensive platform designed to make your travel experiences seamless and memorable. Whether you're a
                     traveler looking to plan a perfect trip, book reliable vehicles, or secure comfortable hotel accommodations, we have you
                      covered. Guides can register to showcase their expertise, while riders can keep their availability updated with easy
                       online/offline status management. From hotel registrations to hassle-free reservations, TourHaven simplifies every step
                        of your journey. Explore, plan, and travel effortlessly with TourHaven!
                </p>
                <div class="social items-center space-x-4 mt-4">
                    <a href="https://github.com/RamithaHeshan33/TourHaven" target="_blank"><i class='bx bxl-github'></i></a>
                    <a href="https://www.linkedin.com/in/ramithaheshan/" target="_blank"><i class='bx bxl-linkedin'></i></a>
                    <a href="https://www.youtube.com/@ramitha33" target="_blank"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
