<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Fetch all artworks
$stmt = $pdo->query("SELECT * FROM artworks ORDER BY created_at DESC"); // Removed 'available = 1'
$artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork Gallery</title>
    <link rel="stylesheet" href="../Assets/gallery.css">
</head>
<body>
<div class="header">
        <!-- Logout button (top right corner) -->
        <a href="loginstruc.php" class="logout-btn">Logout</a>
    </div>

    <h1 class="form-title">Artwork Gallery</h1>
    <div class="artwork-container">
        <?php foreach ($artworks as $artwork): ?>
            <div class="artwork-box">
                <div class="artwork-image">
                    <img src="<?= htmlspecialchars($artwork['image_url']); ?>" alt="<?= htmlspecialchars($artwork['name']); ?>" style="max-width: 100%;">
                </div>
                <div class="artwork-details">
                    <h2><?= htmlspecialchars($artwork['name']); ?></h2>
                    <p><?= htmlspecialchars($artwork['description']); ?></p>
                    <p><strong>Price:</strong>रु<?= number_format($artwork['price'], 2); ?></p>
                    <a href="user-orderstruc.php?id=<?= $artwork['id']; ?>" class="btn">Buy Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
