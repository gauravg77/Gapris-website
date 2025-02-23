<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Check if 'id' exists in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Begin transaction to ensure atomicity
        $pdo->beginTransaction();

        // Delete dependent orders first to avoid foreign key constraint error
        $stmt1 = $pdo->prepare("DELETE FROM orders WHERE artwork_id = :id");
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        // Now delete the artwork
        $stmt2 = $pdo->prepare("DELETE FROM artworks WHERE id = :id");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        // Commit transaction if both deletions succeed
        $pdo->commit();

        // Redirect back to the admin panel with success message
        header("Location: admin-panel.php?message=deleted");
        exit;
    } catch (PDOException $e) {
        // Rollback if any error occurs
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if ID is invalid
    header("Location: admin-panel.php?message=invalid");
    exit;
}
?>
