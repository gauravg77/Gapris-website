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
$popup_class = ''; // Class to toggle visibility

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
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;

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
                $message = "A password reset link has been sent to your email!";
                $popup_class = 'show-popup'; // Show popup
            } else {
                $message = "Failed to send password reset email.";
                $popup_class = 'show-popup'; // Show popup
            }
        } catch (Exception $e) {
            $message = "Error: {$mail->ErrorInfo}";
            $popup_class = 'show-popup'; // Show popup
        }
    } else {
        $message = "No account found with that email.";
        $popup_class = 'show-popup'; // Show popup
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }
        .show-popup {
            display: block;
        }
        .popup button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Popup message -->
<div id="popupMessage" class="popup <?php echo $popup_class; ?>">
    <p><?php echo $message; ?></p>
    <button onclick="closePopup()">OK</button>
</div>

<form action="" method="post">
    <input type="email" name="email" required placeholder="Enter your email">
    <button type="submit">Send Reset Link</button>
</form>

<script>
    function closePopup() {
        document.getElementById("popupMessage").style.display = "none";
    }
</script>

</body>
</html>
