<?php
    require "nav/nav.php";
    require "conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <div class="contact-in">
        <div class="contact-form">
            <h1>Contact Us</h1>
            <p>We're here to help-reach out to us anytime, and we'll respond as soon as possible.</p>
            <form action="https://api.web3forms.com/submit" method="POST">
                <input type="hidden" name="access_key" value="ae11ec17-6145-472d-87ba-bfd9ba032019">
                <input type="text" name="name" placeholder="Name" class="contact-form-txt" required>
                <input type="email" name="email" placeholder="Email" class="contact-form-txt" required>
                <textarea name="message" cols="30" rows="10" placeholder="Message" class="contact-form-txtarea" required></textarea>
                <input type="submit" class="contact-form-btn">
            </form>
            <br>
        </div>

        <div class="contact-pic">
            <img src="../../res/about1.jpg" alt="">
        </div>

    </div> <br> <br> <br>

</body>
</html>