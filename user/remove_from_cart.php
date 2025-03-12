<?php
session_start();

if (!isset($_POST['product_id'])) {
    // If no product ID is provided, redirect back (you can also show an error message)
    header("Location: checkout.php");
    exit();
}

$product_id = intval($_POST['product_id']);

// Check if the cart exists in session
if (isset($_SESSION['cart'])) {
    // Loop through the cart items and remove the item with the matching product_id
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // Assuming each product only appears once in the cart
        }
    }
    // Re-index the cart array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Redirect back to checkout (or furniture.php or any other page)
header("Location: checkout.php");
exit();
?>
