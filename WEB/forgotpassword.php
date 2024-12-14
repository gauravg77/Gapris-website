<?php
require_once 'dbConnect.php'; 
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
                $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expiry,created_at) VALUES (:email, :token, :expiry,:created_at)");
                $stmt->execute(['email' => $email, 'token' => $token, 'expiry' => $expiry,'created_at' => $created_at]);

                // Send the reset link via email
                $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
                $subject = "Password Reset Request";
                $message = "To reset your password, click the following link: $resetLink";
                $headers = "From: no-reply@yourwebsite.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "Password reset link has been sent to your email.";
                } else {
                    echo "Failed to send email. Please try again.";
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