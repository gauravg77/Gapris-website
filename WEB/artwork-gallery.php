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
    <title>ART GALLERY</title>
    <link rel="stylesheet" href="../Assets/gallery.css">
    <style>
        /* Lightbox styles */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(25, 25, 25, 0.7);
            justify-content: center;
            align-items: center;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
        }

        .lightbox .close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
        }

        .artwork-image img {
            cursor: pointer;
        }

        /* New CSS for two flex boxes in one row */
        .artwork-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adjust gap between items if necessary */
        }

        .artwork-box {
            flex: 1 1 45%; /* Allows two items per row, adjusts based on available space */
            box-sizing: border-box;
        }
    </style>
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
                    <img src="<?= htmlspecialchars($artwork['image_url']); ?>" alt="<?= htmlspecialchars($artwork['name']); ?>" 
                         style="max-width: 100%;" onclick="openLightbox('<?= htmlspecialchars($artwork['image_url']); ?>')">
                </div>
                <div class="artwork-details">
                    <h2><?= htmlspecialchars($artwork['name']); ?></h2>
                    <p><?= htmlspecialchars($artwork['description']); ?></p>
                    <p><strong>Price:</strong>रु<?= number_format($artwork['price'], 2); ?></p>
                    <!-- Buy Now button with query parameters for the artwork -->
                    <a href="checkout.php?id=<?= urlencode($artwork['id']); ?>&name=<?= urlencode($artwork['name']); ?>&price=<?= urlencode($artwork['price']); ?>" class="btn">Buy Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Lightbox for full image display -->
    <div id="lightbox" class="lightbox">
        <span class="close" onclick="closeLightbox()">×</span>
        <img id="lightbox-img" src="" alt="Full Image">
    </div>

    <script>
        // Function to open the lightbox
        function openLightbox(imageUrl) {
            document.getElementById('lightbox-img').src = imageUrl;
            document.getElementById('lightbox').style.display = 'flex';
        }

        // Function to close the lightbox
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
    </script>
</body>
</html>
