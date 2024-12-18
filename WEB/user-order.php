<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Check if artwork ID is passed
if (isset($_POST['artwork_id'])) {
    $artwork_id = $_POST['artwork_id'];
    $amount = $_POST['amount']; // Get the price amount

    // You can add your code here to insert the order into the database
    // Assuming you have a table `orders` with necessary columns

    // Insert order into database
    $stmt = $pdo->prepare("INSERT INTO orders (artwork_id, amount, name, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $artwork_id,
        $amount,
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address']
    ]);

    // After processing, you can redirect or show a success message
    echo "Your order has been placed successfully.";
    // You can redirect to a confirmation page or show the success message
    // header("Location: order-success.php");
} else {
    die("No artwork ID provided.");
}
?>
