<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Artwork</title>
    <link rel="stylesheet" href="../Assets/order.css">
</head>
<body>
    <div class="order-container">
        <h1>Order Details</h1>
        <div class="artwork-preview">
            <img src="<?= htmlspecialchars($artwork['image_url']); ?>" alt="<?= htmlspecialchars($artwork['name']); ?>" style="max-width: 200px;">
            <h2><?= htmlspecialchars($artwork['name']); ?></h2>
            <p><strong>Price:</strong> $<?= number_format($artwork['price'], 2); ?></p>
        </div>

        <form action="process-order.php" method="POST">
            <input type="hidden" name="artwork_id" value="<?= $artwork['id']; ?>"> <!-- Hidden artwork ID field -->
            <input type="hidden" name="amount" value="<?= $artwork['price']; ?>"> <!-- Hidden price field -->

            <!-- User Input Fields (keeping them for order processing) -->
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your Full Name">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your Email">

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Your Phone Number">

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" placeholder="Your Full Address"></textarea>

            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
</body>
</html>
