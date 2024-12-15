<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Artwork</title>
</head>
<body>
    <h1>Update Artwork</h1>

    <!-- Form to update artwork -->
    <form action="admin-update.php?id=<?= htmlspecialchars($id); ?>" method="POST" enctype="multipart/form-data">
        <label>Artwork Name:</label>
        <input type="text" name="name" value="<?= isset($artwork['name']) ? htmlspecialchars($artwork['name']) : ''; ?>" required>
        
        <label>Description:</label>
        <textarea name="description" required><?= isset($artwork['description']) ? htmlspecialchars($artwork['description']) : ''; ?></textarea>
        
        <label>Price:</label>
        <input type="number" name="price" value="<?= isset($artwork['price']) ? $artwork['price'] : ''; ?>" step="0.01" required>
        
        <label>Update Image:</label>
        <input type="file" name="image">
        <br>

        <!-- Display current image -->
        <label>Current Image:</label><br>
        <img src="<?= isset($artwork['image_url']) ? $artwork['image_url'] : ''; ?>" alt="Current Image" style="max-width: 200px; max-height: 200px; margin-top: 10px;">
        
        <br><br>
        <button type="submit">Update</button>
        <a href="admin-panel.php">Back to Admin Panel</a>

    </form>
</body>
</html>
