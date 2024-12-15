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
                ':created_at'=>$created_at
            ]);
            echo "Artwork uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid image file.";
    }
}
?>

<!-- HTML form -->
<form action="admin-upload.php" method="POST" enctype="multipart/form-data">
    <label>Artwork Name:</label>
    <input type="text" name="name" required>
    <label>Description:</label>
    <textarea name="description" required></textarea>
    <label>Price:</label>
    <input type="number" name="price" step="0.01" required>
    <label>Upload Image:</label>
    <input type="file" name="image" id="image-input" required onchange="previewImage(event)">
    <div id="preview-container">
        <p>No image selected</p>
        <img id="preview" style="display:none; max-width: 300px; max-height: 300px;">
    </div>
    <button type="submit" name="submit">Upload</button>
</form>


<script>
    // Function to preview the image
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('preview-container');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                previewContainer.querySelector('p').style.display = 'none';
            };

            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            previewContainer.querySelector('p').style.display = 'block';
        }
    }
</script>