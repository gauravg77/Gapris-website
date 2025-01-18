<?php
// Include necessary files for PHPMailer and database connection
require './utils/PHPMailer/src/PHPMailer.php';
require './utils/PHPMailer/src/SMTP.php';
require './utils/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Update with your actual paths and database details
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
        date_default_timezone_set('Asia/Kathmandu'); // Set the time zone to Nepal Time (NPT)

        // User found, generate a unique token for password reset
        $token = bin2hex(random_bytes(50)); // 50 bytes = 100 characters
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour

        // Update the user_login table with the reset token and expiry
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Prepare the reset password link
        $reset_link = "hello" . $token;

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
            $mail->addAddress($email); // Send the email to the user
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "You requested a password reset. Click the link below to reset your password:\n\n" .
                          "$reset_link\n\n" .
                          "If you did not request this, please ignore this email.";

            // Send the email and check if it was successful
            if ($mail->send()) {
                $message = "A password reset link has been sent to your email!";
            } else {
                $message = "Failed to send password reset email.";
            }
        } catch (Exception $e) {
            $message = "Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with that email.";
    }

    // Redirect to the same page with a success or error message
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}

// Handle messages passed through the query string
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>
