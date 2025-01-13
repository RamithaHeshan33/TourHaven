<?php
session_start();
require('../../conn.php');
require('../nav.php');


// Fetch form data
$name = $_POST['name'];
$email = $_POST['email'];
$team_number = $_POST['team_number'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$destination = $_POST['destination'];
$st_date = $_POST['st_date'];
$end_date = $_POST['end_date'];
$remakes = $_POST['remakes'];

// Save form data in session
$_SESSION['trip_details'] = compact('name', 'email', 'team_number', 'phone', 'address', 'destination', 'st_date', 'end_date', 'remakes');

// Fetch available vehicles
$sql = "SELECT v.*, g.email as guider_email, g.name as guider_name  FROM vehicles v JOIN guiders g ON v.email = g.email";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Vehicle</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .body {
            margin: 100px 50px 0 50px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            color: #4CAF50;
            text-transform: uppercase;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .card {
            display: flex;
            background: #fff;
            gap: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 1200px;
            /* text-align: center; */
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-content {
            padding: 15px;
        }
        .card-content h2 {
            margin: 10px 0;
            font-size: 1.5em;
            color: #333;
        }
        .card-content p {
            color: #666;
            font-size: 0.9em;
            margin: 5px 0 15px;
        }
        .card-content .price {
            font-weight: bold;
            font-size: 1.2em;
            color: #28a745;
        }
        .card-content form {
            margin: 0;
        }
        .card-content form button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .card-content form button:hover {
            background-color: #45a049;
        }
        .card-img {
            max-width: 400px;
        }
        .card-img img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="body">
        <h1 class="title">Select a Vehicle</h1>
        <div class="container">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-img">
                    <img src="<?php echo htmlspecialchars($row['main_image']); ?>" alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                </div>
                <div class="card-content">
                    <h2><?php echo htmlspecialchars($row['vehicle_name']); ?></h2>
                    <p>Type: <?php echo htmlspecialchars($row['vehicle_type']); ?></p>
                    <p>owner name: <?php echo htmlspecialchars($row['guider_name']); ?></p>
                    <p>Owner: <?php echo htmlspecialchars($row['guider_email']); ?></p>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="price">Rs.<?php echo number_format($row['price'], 2); ?> (per kilometer)</p>
                    <form action="payment/checkout.php" method="POST">
                        <input type="hidden" name="vehicle_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="guider_email" value="<?php echo $row['guider_email']; ?>">
                        <button type="submit">Book This Vehicle</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
