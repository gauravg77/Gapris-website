<!-- HTML form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Artwork</title>
    <link rel="stylesheet" href="../Assets/upload.css">

</head>
<body>
    <h1>Upload Artwork</h1>
<form action="admin-upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
    <label for="name" class="form-label">Artwork Name:</label>
    <input type="text" name="name" id="name" class="form-input" required>
    
    <label for="description" class="form-label">Description:</label>
    <textarea name="description" id="description" class="form-input" required></textarea>
    
    <label for="price" class="form-label">Price:</label>
    <input type="number" name="price" id="price" class="form-input" step="0.01" required>
    
    <label for="image" class="form-label">Upload Image:</label>
    <input type="file" name="image" id="image-input" class="form-input" required onchange="previewImage(event)">
    
    <div id="preview-container" class="preview-container">
        <p>No image selected</p>
        <img id="preview" class="preview-image" style="display:none;">
    </div>
    
    <button type="submit" name="submit" class="btn-submit">Upload</button>
    <br>
    <a href="admin-panel.php">Back to Admin Panel</a>

</form>
</body>
</html>


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