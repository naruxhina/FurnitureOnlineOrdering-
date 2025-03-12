<?php
session_start();
include '../assets/package/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Prepare and execute a query to fetch product details from the database
    $stmt = $conn->prepare("SELECT product_name, price, product_image FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name, $price, $product_image);
    $stmt->fetch();
    $stmt->close();

    // Check if product details were fetched successfully
    if (!$product_name) {
        echo json_encode(["status" => "error", "message" => "Product not found!"]);
        exit();
    }
    
    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product is already in the cart and update quantity; otherwise, add as new item
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id'    => $product_id,
            'product_name'  => $product_name,
            'price'         => $price,
            'quantity'      => 1,
            'product_image' => $product_image
        ];
    }
    
    echo json_encode(["status" => "success", "message" => "Product added to cart!"]);
    exit();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}
?>
