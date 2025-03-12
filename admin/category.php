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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category_name = $_POST['category_name'];
        $category_date = date("Y-m-d");

        $stmt = $conn->prepare("INSERT INTO category (category_name, category_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $category_name, $category_date);
        if ($stmt->execute()) {
            echo "Category added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Category Management</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="upload/assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="upload/css/styles.css" rel="stylesheet" />
</head>
<body>
<div class="d-flex" id="wrapper">
        <?php
        include "upload/navigation.php";
        ?>
        <div id="page-content-wrapper">
            <?php
            include "upload/header.php";
            ?>
           <div class="container-fluid py-4 px-5 bg-light shadow rounded">
    <h2 class="text-danger mb-4 fw-bold">Add New Category</h2>
    <form method="POST" class="border p-4 bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Category Name:</label>
            <input type="text" name="category_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger">Add Category</button>
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
