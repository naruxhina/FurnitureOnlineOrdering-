<?php
session_start();
include 'assets/package/db.php';

// If the form is submitted, process the data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $category_id   = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $product_name  = trim($_POST['product_name']);
    $product_date  = !empty($_POST['product_date']) ? $_POST['product_date'] : null;
    $price         = intval($_POST['price']);
    $quantity      = intval($_POST['quantity']);
    $status        = $_POST['status'];
    $description   = trim($_POST['description']);
    
    // Handle file upload for product_image
    $product_image = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $filename   = basename($_FILES['product_image']['name']);
        $tmp_name   = $_FILES['product_image']['tmp_name'];
        // Define a directory where images will be stored (make sure this directory exists and is writable)
        $target_dir = "uploads/";
        // Create a unique file name to avoid overwriting existing files
        $target_file = $target_dir . time() . '_' . $filename;
        
        // (Optional) Validate file type/size here
        
        if (move_uploaded_file($tmp_name, $target_file)) {
            $product_image = $target_file; // Save file path into the database
        } else {
            $error = "Error uploading image.";
        }
    } else {
        $error = "Image upload failed. Please select a valid image.";
    }

    // Insert product into database if no error so far
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO product (category_id, product_name, product_date, price, quantity, status, description, product_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Adjust bind types: i = integer, s = string, d = double. Here we assume:
        // category_id (int), product_name (string), product_date (string), price (int), quantity (int), status (string), description (string), product_image (string)
        $stmt->bind_param("issdiiss", $category_id, $product_name, $product_date, $price, $quantity, $status, $description, $product_image);
        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Product</title>
  <!-- Bootstrap CSS -->
  <link rel="icon" type="image/x-icon" href="assets/icon/favicon.ico"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h1>Add New Product</h1>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <form action="add_product.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="category_id" class="form-label">Category ID</label>
      <input type="number" name="category_id" class="form-control" id="category_id" required>
    </div>
    <div class="mb-3">
      <label for="product_name" class="form-label">Product Name</label>
      <input type="text" name="product_name" class="form-control" id="product_name" required>
    </div>
    <div class="mb-3">
      <label for="product_date" class="form-label">Product Date</label>
      <input type="date" name="product_date" class="form-control" id="product_date">
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price</label>
      <input type="number" name="price" class="form-control" id="price" required>
    </div>
    <div class="mb-3">
      <label for="quantity" class="form-label">Quantity</label>
      <input type="number" name="quantity" class="form-control" id="quantity" required>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" class="form-select" id="status" required>
        <option value="available">Available</option>
        <option value="out of stock">Out of Stock</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label for="product_image" class="form-label">Product Image</label>
      <input type="file" name="product_image" class="form-control" id="product_image" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Product</button>
  </form>
</div>
<!-- Bootstrap JS --> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
