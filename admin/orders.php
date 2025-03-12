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

// Update status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        echo "<script>alert('Order status updated successfully!');</script>";
    }
    $stmt->close();
}

// Fetch orders with JOIN
$result = $conn->query("
        SELECT 
            orders.order_id, 
            users.user_name, 
            product.product_name, 
            orders.order_date, 
            orders.status 
        FROM orders
        JOIN users ON orders.user_id = users.user_id
        JOIN product ON orders.product_id = product.product_id
    ");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Order Management</title>
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
            <div class="container mt-5">
        <h2 class="text-danger mb-4 fw-bold text-center">Order Management</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="dispatch" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>pending</option>
                                        <option value="ready to ship" <?php if ($row['status'] == 'ready to ship') echo 'ready to ship'; ?>>ready to ship</option>
                                        <option value="delivered" <?php if ($row['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                                        <option value="cancelled" <?php if ($row['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="edit_order.php?order_id=<?php echo $row['order_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_order.php?order_id=<?php echo $row['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="upload/js/scripts.js"></script>

</body>

</html>

<?php
$conn->close();
?>