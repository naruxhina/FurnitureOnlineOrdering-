<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "japan_surplus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories for dropdown
$categories = $conn->query("SELECT category_id, category_name FROM category");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id   = $_POST['category_id'];
    $product_name  = $_POST['product_name'];
    $product_date  = date("Y-m-d");
    $price         = $_POST['price'];
    $quantity      = $_POST['quantity'];
    $description   = $_POST['description'];

    // Determine status based on quantity
    $status = ($quantity > 0) ? "available" : "out of stock";

    // Check if product name is unique
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE product_name = ?");
    $check_stmt->bind_param("s", $product_name);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        echo "Error: Product name must be unique.";
    } else {
        if ($price < 0 || $quantity < 0) {
            echo "Error: Price and quantity cannot be negative.";
        } else {
            // Process single image upload
            $product_image = "";
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "../upload/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $filename    = basename($_FILES['product_image']['name']);
                $target_file = $target_dir . time() . "_" . $filename;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_types = ["jpg", "jpeg", "png", "gif"];

                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                        $product_image = $target_file;
                    } else {
                        echo "Error uploading image.";
                        exit();
                    }
                } else {
                    echo "Invalid file type.";
                    exit();
                }
            } else {
                echo "Image upload error.";
                exit();
            }

            // Insert product details including product_image into product table
            $stmt = $conn->prepare("INSERT INTO product (category_id, product_name, product_date, price, quantity, status, description, product_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            // Binding parameters: i = category_id, s = product_name, s = product_date, s = price, i = quantity, s = status, s = description, s = product_image
            $stmt->bind_param("isssisss", $category_id, $product_name, $product_date, $price, $quantity, $status, $description, $product_image);

            if ($stmt->execute()) {
                echo "Product added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Product Management</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="upload/assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="upload/css/styles.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include "upload/navigation.php"; ?>
        <div id="page-content-wrapper">
            <?php include "upload/header.php"; ?>
            <div class="container-fluid py-4 px-5 bg-light shadow rounded">
                <h2 class="text-danger mb-4 fw-bold">Add New Product</h2>
                <form method="POST" enctype="multipart/form-data" class="border p-4 bg-white rounded shadow-sm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category:</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php while ($row = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $row['category_id']; ?>"> 
                                    <?php echo $row['category_name']; ?> 
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name:</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price:</label>
                            <input type="text" name="price" class="form-control" pattern="^[0-9]+(\.[0-9]{1,2})?$" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantity:</label>
                            <input type="text" name="quantity" class="form-control" pattern="^[0-9]+$" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Image:</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description:</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Add Product</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="upload/js/scripts.js"></script>
</body>

</html>
