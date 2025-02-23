<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Check if ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $artwork_id = $_GET['id'];

    // Fetch the artwork details for the given ID
    $stmt = $pdo->prepare("SELECT * FROM artworks WHERE id = :id");
    $stmt->bindParam(':id', $artwork_id, PDO::PARAM_INT);
    $stmt->execute();

    $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the artwork exists
    if (!$artwork) {
        die("Artwork not found!");
    }
} else {
    die("Invalid artwork ID.");
}

// Handle the form submission to update the artwork
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $artwork['image_url']; // Default to the current image URL

    // Check if a new image has been uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $divId = "artwork_" . $artwork['id'];  

        // Define a unique filename for the new image
        $new_image_name = uniqid('artwork_') . '.' . $image_ext;
        $upload_dir = '../uploads/images/';
        
        // Delete the old image file (if it exists) to prevent orphan files
        if (file_exists($artwork['image_url'])) {
            unlink($artwork['image_url']);
        }
        
        // Move the uploaded file to the desired directory
        if (move_uploaded_file($image_tmp, $upload_dir . $new_image_name)) {
            $image_url = $upload_dir . $new_image_name; // Update image URL to the new file path
        } else {
            echo "Error uploading image.";
        }
    }

    // Update the artwork in the database with the new image URL
    $update_stmt = $pdo->prepare("UPDATE artworks SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id");
    $update_stmt->bindParam(':name', $name);
    $update_stmt->bindParam(':description', $description);
    $update_stmt->bindParam(':price', $price);
    $update_stmt->bindParam(':image_url', $image_url);
    $update_stmt->bindParam(':id', $artwork_id);

    if ($update_stmt->execute()) {
        // Redirect to the same page to reload the updated artwork
        header("Location: admin-panel.php");
        exit;
    } else {
        echo "Error updating artwork.";
    }
    header("Location: admin-panel.php");

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artwork</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #120d1d, #012412);
            color: white;
            text-align: center;
            padding: 20px;
        }
        form {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            text-align: left;
            width: 60%;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            outline: none;
        }
        button {
            padding: 10px 15px;
            border: none;
            background: #00d9ff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        button:hover {
            background: #007bb5;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            color: #00d9ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        img {
            max-width: 200px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Edit Artwork</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Artwork Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($artwork['name']); ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($artwork['description']); ?></textarea><br>

        <label for="price">Price (NRs):</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($artwork['price']); ?>" required><br>

        <label for="image_url">Image URL:</label>
        <input type="text" name="image_url" id="image_url" value="<?= htmlspecialchars($artwork['image_url']); ?>" disabled><br>

        <div id="<?= $divId; ?>">
            <label>Current Image:</label><br>
            <img src="<?= htmlspecialchars($artwork['image_url']); ?>" alt="Artwork Image"><br>
        </div>

        <label for="image">Upload New Image:</label>
        <input type="file" name="image" id="image" accept="image/*"><br>

        <button type="submit">Update Artwork</button>
        <a href="admin-panel.php">Cancel</a>
    </form>
</body>
</html>
