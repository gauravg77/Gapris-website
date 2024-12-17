<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Check if 'id' exists in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare the DELETE SQL statement
        $stmt = $pdo->prepare("DELETE FROM artworks WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the main admin panel with success message
            header("Location: admin-panel.php?message=deleted");
            exit;
        } else {
            // Redirect with error message
            header("Location: admin-panel.php?message=error");
            exit;
        }
    } catch (PDOException $e) {
        // Error handling
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if ID is invalid
    header("Location: admin-panel.php?message=invalid");
    exit;
}
?>
