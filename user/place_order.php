<?php
session_start();
include '../assets/package/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo json_encode(["status" => "error", "message" => "Your cart is empty."]);
    exit();
}

// Retrieve checkout form data, including shipping address
$mode_of_payment = $_POST['mode_of_payment'] ?? '';
$amount_pay      = $_POST['amount_pay'] ?? '';
$gref            = $_POST['gref'] ?? '';
$gnumber         = $_POST['gnumber'] ?? '';
$address         = $_POST['address'] ?? '';

// Basic validation
if (empty($mode_of_payment) || empty($amount_pay) || empty($gref) || empty($gnumber) || empty($address)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Set default order status (e.g., 'dispatch')
$status = 'dispatch';

// Process each cart item: Insert one order record per cart item (storing the full quantity)
foreach ($cart as $item) {
    $product_id    = $item['product_id'];
    $quantity      = intval($item['quantity']);
    $product_name  = $item['product_name'];
    $product_image = $item['product_image']; // image path from cart

    // Prepare the INSERT statement for the orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, product_name, quantity, order_date, status, mode_of_payment, amount_pay, gref, gnumber, product_image, address) VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit();
    }
    // Bind parameters:
    // i - user_id, i - product_id, s - product_name, i - quantity,
    // s - status, s - mode_of_payment, d - amount_pay, s - gref, s - gnumber, s - product_image, s - address
    $stmt->bind_param("iisissdssss", $user_id, $product_id, $product_name, $quantity, $status, $mode_of_payment, $amount_pay, $gref, $gnumber, $product_image, $address);
    $stmt->execute();
    if ($stmt->error) {
        echo json_encode(["status" => "error", "message" => "Error executing query: " . $stmt->error]);
        exit();
    }
    $stmt->close();

    // Subtract the ordered quantity from the product's stock
    $update_stmt = $conn->prepare("UPDATE product SET quantity = quantity - ? WHERE product_id = ?");
    if (!$update_stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed (update): " . $conn->error]);
        exit();
    }
    $update_stmt->bind_param("ii", $quantity, $product_id);
    $update_stmt->execute();
    if ($update_stmt->error) {
        echo json_encode(["status" => "error", "message" => "Error updating product quantity: " . $update_stmt->error]);
        exit();
    }
    $update_stmt->close();
}

// Clear the cart after placing the order
$_SESSION['cart'] = [];

// Return JSON success response
echo json_encode(["status" => "success", "message" => "Your order has been placed successfully!"]);
exit();
?>
