<?php
session_start();
require 'conn.php';

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['users'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $users = $_POST['users'];

    $table = $users == 'tourists' ? 'tourists' : 'guiders';
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = 'User not found';
    } elseif (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $redirect = $users == 'tourists' ? 'index1.php' : 'Guiders/index.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = 'Invalid password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg,rgb(103, 226, 107),rgb(55, 138, 57));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            margin: auto;
        }

        .login-card {
            background-color: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
            padding: 20px 30px;
            text-align: center;
        }

        .login-card h2 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }

        .login-card p {
            font-size: 14px;
            margin: 10px 0 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .login-btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .login-btn:hover {
            background-color: #4CAF50;
        }

        .error {
            color: #d9534f;
            margin-bottom: 15px;
        }

    </style>

</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Welcome Back!</h2>
            <p>Please login to access your dashboard</p>
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="users">Login as</label>
                    <select id="users" name="users" required>
                        <option value="tourists">Tourist</option>
                        <option value="guiders">Guider</option>
                    </select>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
