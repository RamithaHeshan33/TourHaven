<?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("location: login.php");
        exit();
    }
    require "../nav.php";
    require "../../conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Buddy - Chat Assistance</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 98.5%;
            background-color: #f4f4f9;
        }

        /* Header Styling */
        header {
            background-color: #004d7a;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Main Content Styling */
        .container {
            display: flex;
            flex-direction: column;
            height: calc(100% - 50px);
        }

        iframe {
            flex: 1;
            border: none;
        }

        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        Budget Buddy - Your Travel Assistant
    </header>

    <!-- Main Content -->
    <div class="container">
        <iframe src="https://copilotstudio.microsoft.com/environments/Default-189dc61c-769b-4048-8b0f-6de074bba26c/bots/cr10a_tourHaven/webchat?__version__=2"></iframe>
    </div>
</body>
</html>
