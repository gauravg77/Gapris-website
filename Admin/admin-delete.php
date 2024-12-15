<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM artworks WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "Artwork deleted successfully.";
        header("Location: admin-panel.php"); // Redirect to the admin panel after deletion
        exit();
    } catch (PDOException $e) {
        echo "Error deleting artwork: " . $e->getMessage();
        exit();
    }
} else {
    echo "No ID parameter provided.";
    exit();
}
?>
