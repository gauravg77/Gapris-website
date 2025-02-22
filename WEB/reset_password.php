<?php

require 'dbConnect.php'; // Include database connection

if (!isset($_GET['token'])) {
    die("Invalid request!");
}

$token = $_GET['token'];

// Check if the token exists and is not expired
$stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ? AND expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token!");
}

$email = $result->fetch_assoc()['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = $_POST['password'];

    // Server-side validation to check password length
    if (strlen($new_password) < 8) {
        die("Password must be at least 8 characters long!");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();
    // Redirect to login page
    header("Location: loginstruc.php");
    exit(); // Stop script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Including the CSS directly in the file -->
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            background: linear-gradient(to right,#120d1d, #012412); /* Darker gradient */
            color: white;
            overflow: hidden;
        }

        /* Container for the login form */
        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Form Title */
        h1.form-title {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        /* Input Group Styling */
        .input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .input-group input {
            border: none;
            outline: none;
            background: transparent;
            color: white;
            width: 100%;
            font-size: 1rem;
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Submit Button */
        .btn {
            width: 100%;
            padding: 0.8rem;
            margin-top: 1rem;
            background: #00d9ff;
            border: none;
            outline: none;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .btn:hover {
            background: #007bb5;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="form-title">Reset Password</h1>
        <form action="" method="post">
            <div class="input-group">
                <input type="password" name="password" required minlength="8" placeholder="Enter new password (min. 8 characters)">
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
