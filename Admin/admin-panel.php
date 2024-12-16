<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Fetch all artworks from the database
$stmt = $pdo->query("SELECT * FROM artworks ORDER BY created_at DESC");
$artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Artworks</title>
    <link rel="stylesheet" href="../Assets/displaystyle.css">
</head>
<body>
    <h1 class="form-title">Admin Panel - Manage Artworks</h1>
    
    <!-- Top-right buttons -->
    <a href="admin-uploadstruc.php" class="btn-add">Add Artwork</a>
    <div class="artwork-container">
        <?php foreach ($artworks as $artwork): ?>
            <div class="artwork-box">
                <div class="artwork-image">
                    <img src="<?= $artwork['image_url']; ?>" alt="<?= htmlspecialchars($artwork['name']); ?>" style="max-width: 100%;">
                </div>
                <div class="art-title"c>
                <h2><?= htmlspecialchars($artwork['name']); ?></h2>
        </div>
                <div class="artwork-details">
                    
                    <p><?= htmlspecialchars($artwork['description']); ?></p>
                    <p><strong>Price:</strong> $<?= number_format($artwork['price'], 2); ?></p>
                </div>
                <div class="artwork-actions">
                    <a href="admin-updatestruc.php?id=<?= $artwork['id']; ?>" class="btn">Edit</a>
                    <a href="admin-deletestruc.php?id=<?= $artwork['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')" class="btn delete-btn">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
