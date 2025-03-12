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
    echo "Invalid order ID.";
    exit();
}

$order_id = $_GET['order_id'];

// Delete order
$stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    echo "<script>alert('Order deleted successfully!'); window.location.href = 'orders.php';</script>";
} else {
    echo "Error deleting order.";
}

$stmt->close();
$conn->close();
?>
