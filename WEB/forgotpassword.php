<?php 
require_once 'dbConnect.php'; 
require 'vendor/autoload.php';  // Ensure PHPMailer is included
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$errors = []; // Array to store errors

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $created_at = date('Y-m-d H:i:s');

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    if (empty($errors)) {
        try {
            // Check if the email exists in the database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user)  {
                // Generate a password reset token (a unique string)
                $token = bin2hex(random_bytes(50));
                $expiry = time() + 3600; // Token will expire in 1 hour

                // Insert token into the database
                $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expiry, created_at) VALUES (:email, :token, :expiry, :created_at)");
                $stmt->execute(['email' => $email, 'token' => $token, 'expiry' => $expiry, 'created_at' => $created_at]);

                // Use PHPMailer to send the email
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';          // SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'merochitra8@gmail.com'; // Your email
                    $mail->Password = 'your-email-password';  // Your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Sender and recipient settings
                    $mail->setFrom('merochitra8@gmail.com', 'Your Website');
                    $mail->addAddress($email);

                    // Email content
                    $resetLink = "http://website.com/reset_password.php?token=$token";
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "<p>To reset your password, click the following link:</p>
                                   <a href='$resetLink'>Reset Password</a>
                                   <p>This link will expire in 1 hour.</p>";

                    // Send the email
                    $mail->send();
                    echo "Password reset link has been sent to your email.";
                } catch (Exception $e) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                $errors['email'] = "No account found with that email address.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Display errors if any
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}
?>
