<?php
// Include necessary files for PHPMailer and database connection
require './utils/PHPMailer/src/PHPMailer.php';
require './utils/PHPMailer/src/SMTP.php';
require './utils/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'constants.php';
require 'dbConnect.php'; // Include your database connection file

$message = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate form inputs
    $email = htmlspecialchars($_POST['email']);

    // Check if the email exists in the user_login table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        date_default_timezone_set('Asia/Kathmandu'); // Set Nepal Time (NPT)

        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50)); 
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); 

        // Update the database with the reset token and expiry
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Prepare the reset password link
        $reset_link = "http://localhost/Gapris-website/WEB/reset_password.php?token=" . $token;

        // PHPMailer setup
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your-email@gmail.com', 'Gapris Collection');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "You requested a password reset. Click the link below to reset your password:\n\n" . 
                          "$reset_link\n\n" . 
                          "If you did not request this, please ignore this email.";

            // Send the email and check if successful
            if ($mail->send()) {
                $message = "A password reset link has been sent to your email! Please check your inbox.";
            } else {
                $message = "Failed to send password reset email.";
            }
        } catch (Exception $e) {
            $message = "Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    
    <!-- Include the CSS directly in the file -->
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

<?php
// Show the alert message using JavaScript
if ($message) {
    echo "<script>alert('$message');</script>";
}
?>

<div class="container">
    <h1 class="form-title">Password Reset</h1>
    <form action="" method="post">
        <div class="input-group">
            <input type="email" name="email" required placeholder="Enter your email">
        </div>
        <button type="submit" class="btn">Send Reset Link</button>
    </form>
</div>

</body>
</html>
