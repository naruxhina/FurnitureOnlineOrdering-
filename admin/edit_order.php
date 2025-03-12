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

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    echo "<script>alert('Invalid order ID.'); window.location.href = 'orders.php';</script>";
    exit();
}

$order_id = intval($_GET['order_id']); // Sanitize input

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href = 'orders.php';</script>";
    exit();
}

// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Order updated successfully!'); window.location.href = 'orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order.');</script>";
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="upload/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Edit Order Status</h2>
        <form method="POST">
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Current Status:</strong> <?php echo ucfirst($order['status']); ?></p>
            <label for="status">Change Status:</label>
            <select name="status" id="status">
                <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="ready to ship" <?php if ($order['status'] == 'ready to ship') echo 'selected'; ?>>Ready to Ship</option>
                <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                <option value="cancelled" <?php if ($order['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>
            <br><br>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="orders.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
