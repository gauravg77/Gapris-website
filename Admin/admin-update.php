<?php
require_once '../Includes/dbConnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM artworks WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the artwork was found
    if (!$artwork) {
        echo "Artwork not found.";
        exit; // Stop execution if the artwork does not exist
    }
} else {
    echo "Invalid request.";
    exit; // Stop execution if no 'id' is provided in the URL
}

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    if ($_FILES['image']['name']) {
        $target_file = "../Assets/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $stmt = $pdo->prepare("UPDATE artworks SET name=:name, description=:description, price=:price, image_url=:image_url WHERE id=:id");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image_url' => $target_file,
            ':id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE artworks SET name=:name, description=:description, price=:price WHERE id=:id");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':id' => $id
        ]);
    }
    echo "Artwork updated successfully.";
}
?>

<form action="admin-update.php?id=<?= $artwork['id'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $artwork['id'] ?>">

    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($artwork['name']) ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?= htmlspecialchars($artwork['description']) ?></textarea>

    <label>Price:</label>
    <input type="number" name="price" value="<?= htmlspecialchars($artwork['price']) ?>" required>

    <label>Current Image:</label>
    <?php if ($artwork['image_url']) { ?>
        <img src="<?= htmlspecialchars($artwork['image_url']) ?>" alt="Artwork Image" style="max-width: 300px; max-height: 300px; display: block;">
    <?php } else { ?>
        <p>No image uploaded</p>
    <?php } ?>

    <label>Upload New Image (Optional):</label>
    <input type="file" name="image" id="image-input" onchange="previewImage(event)">

    <div id="preview-container">
        <p>No image selected</p>
        <img id="preview" style="display:none; max-width: 300px; max-height: 300px;">
    </div>

    <button type="submit" name="submit">Update</button>
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
