<?php
require_once '../Includes/dbConnect.php'; // Include database connection

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = trim($_GET['id']); // Clean the ID

    // Fetch the specific artwork from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM artworks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if artwork is found
        if (!$artwork) {
            echo "Artwork not found for ID: $id";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error fetching artwork: " . $e->getMessage();
        exit();
    }

} else {
    echo "No ID parameter in URL.";
    exit();
}

// Handle form submission for updating artwork
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $artwork['image_url']; // Default to existing image URL

    // Check if a new image file was uploaded
    if (!empty($_FILES["image"]["tmp_name"])) {
        $target_dir = "../Assets/uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file; // Update the image URL
        } else {
            echo "Error uploading image.";
        }
    }

    // Update the artwork in the database
    try {
        $stmt = $pdo->prepare("UPDATE artworks SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image_url' => $image_url,
            ':id' => $id
        ]);

        echo "Artwork updated successfully.";
        header("Location: admin-panel.php"); // Redirect to admin panel after update
        exit();
    } catch (PDOException $e) {
        echo "Error updating artwork: " . $e->getMessage();
        exit();
    }
}
?>
