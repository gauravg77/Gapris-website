<?php
// require_once '../Includes/header.php'; // Include header //include footer at last when created 
require_once '../Includes/dbConnect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $created_at = date('Y-m-d H:i:s');


    // Handling file upload
    $target_dir = "../Assets/uploads/"; // Store uploads in Assets/uploads
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image is valid
    if (getimagesize($_FILES["image"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Save to database
            $stmt = $pdo->prepare("INSERT INTO artworks (name, description, price, image_url,created_at) 
                                   VALUES (:name, :description, :price, :image_url,:created_at)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':image_url' => $target_file,
                ':created_at'=>$created_at,
                // ':available' => 1
            ]);
            echo "<script>alert('Artwork uploaded successfully.');</script>";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid image file.";
    }
}
?>

