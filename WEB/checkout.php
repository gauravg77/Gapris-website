<?php
session_start();
require_once '../Includes/dbConnect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user details from session
$userId = $_SESSION['user']['id'];
$userName = $_SESSION['user']['name'];
$userEmail = $_SESSION['user']['email'];

// Get artwork details from URL parameters
$artworkId = isset($_GET['id']) ? $_GET['id'] : '';
$artworkName = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$artworkPrice = isset($_GET['price']) ? $_GET['price'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khalti Payment Integration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="m-4">
    <?php
    if (isset($_SESSION['transaction_msg'])) {
        echo $_SESSION['transaction_msg'];
        unset($_SESSION['transaction_msg']);
    }

    if (isset($_SESSION['validate_msg'])) {
        echo $_SESSION['validate_msg'];
        unset($_SESSION['validate_msg']);
    }
    ?>
    <h1 class="text-center">Khalti Payment Integration</h1>
    <div class="d-flex justify-content-center mt-3">
        <form class="row g-3 w-50 mt-4" action="payment-request.php" method="POST">
            <label for="">Product Details:</label>
            <div class="col-md-6">
                <label for="inputAmount4" class="form-label">Amount</label>
                <input type="text" class="form-control" id="inputAmount4" name="inputAmount4" value="<?= htmlspecialchars($artworkPrice); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputPurchasedOrderId4" class="form-label">Purchased Order Id</label>
                <input type="text" class="form-control" id="inputPurchasedOrderId4" name="inputPurchasedOrderId4" value="<?= htmlspecialchars($artworkId); ?>" readonly>
            </div>
            <div class="col-12">
                <label for="inputPurchasedOrderName" class="form-label">Purchased Order Name</label>
                <input type="text" class="form-control" id="inputPurchasedOrderName" name="inputPurchasedOrderName" value="<?= htmlspecialchars($artworkName); ?>" readonly>
            </div>
            <label for="">Customer Details:</label>
            <div class="col-12">
                <label for="inputName" class="form-label">Name</label>
                <input type="text" class="form-control" id="inputName" name="inputName" value="<?= htmlspecialchars($userName); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="text" class="form-control" id="inputEmail" name="inputEmail" value="<?= htmlspecialchars($userEmail); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="inputPhone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="inputPhone" name="inputPhone" required>
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label">Address (You can describe the landmarks as well)</label>
                <input type="text" class="form-control" id="inputAddress" name="inputAddress" required>
            </div>
            <div class="col-12">
                <button type="submit" name="submit" class="btn btn-primary">Pay with Khalti</button>
            </div>
        </form>
    </div>
</body>
</html>
