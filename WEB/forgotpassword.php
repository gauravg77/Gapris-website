<?php
require 'utils/PHPMailer/src/PHPMailer.php';
require 'utils/PHPMailer/src/SMTP.php';
require 'utils/PHPMailer/src/Exception.php';
require 'constants.php'; // Contains database connection details, SMTP_HOST, SMTP_USERNAME, SMTP_PASSWORD

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    try {
        // Database connection
        $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email exists in the users table
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Generate unique token
            $token = bin2hex(random_bytes(32));
            $expiry = time() + 3600; // Token valid for 1 hour

            // Insert token into password_resets table
            $stmt = $pdo->prepare(
                "INSERT INTO password_resets (email, token, expiry) VALUES (:email, :token, :expiry)"
            );
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expiry', $expiry);
            $stmt->execute();

            // Prepare the reset link
            $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";

            // Send the reset email
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USERNAME;
                $mail->Password = SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom(SMTP_USERNAME, 'Your Website');
                $mail->addAddress($email);

                $mail->Subject = 'Password Recovery';
                $mail->Body = "Hi,\n\nClick the link below to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.";

                if ($mail->send()) {
                    echo "A password reset link has been sent to your email.";
                } else {
                    echo "Failed to send the email.";
                }
            } catch (Exception $e) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "No account found with this email.";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
