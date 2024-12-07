<?php
require_once 'dbConnect.php';

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    // Sanitize and validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $created_at = date('Y-m-d H:i:s');

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Name validation
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    // Password length validation
    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    }

    // Confirm password validation
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // Check for existing user in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    if (!$stmt->execute(['email' => $email])) {
        var_dump($stmt->errorInfo());
        exit();
    }
    if ($stmt->fetch()) {
        $errors['user_exist'] = 'Email is already registered';
    }

    // Redirect if there are errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: register.php');
        exit();
    }

    // Hash the password and insert the user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, created_at) VALUES (:email, :password, :name, :created_at)");
    if (!$stmt->execute(['email' => $email, 'password' => $hashedPassword, 'name' => $name, 'created_at' => $created_at])) {
        var_dump($stmt->errorInfo());
        exit();
    }

    // Redirect to login on success
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    // Sanitize and validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Password validation
    if (empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }

    // Redirect if there are errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    // Check for the user in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    if (!$stmt->execute(['email' => $email])) {
        var_dump($stmt->errorInfo());
        exit();
    }
    $user = $stmt->fetch();

    // Verify password and handle login
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'created_at' => $user['created_at']
        ];

        // Redirect to home on success
        header('Location: home.php');
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }
}
