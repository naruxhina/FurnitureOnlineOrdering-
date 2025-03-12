<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$cart = $_SESSION['cart'] ?? [];
$totalAmount = 0;
if (!empty($cart)) {
    foreach ($cart as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .container {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <!-- Header is always displayed -->
  <?php include "uploads/header.php"; ?>

  <div class="container mt-4">
    <h1 class="mb-4">Checkout</h1>
    
    <?php if (empty($cart)): ?>
      <!-- Message if cart is empty -->
      <div class="alert alert-info">
        Your cart is empty.
      </div>
      <a href="furniture.php" class="btn btn-outline-danger">Continue Shopping</a>
    <?php else: ?>
      <!-- Order Summary -->
      <h3>Order Summary</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
            <tr>
              <td>
                <?php if (!empty($item['product_image'])): ?>
                  <img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width:50px; height:50px; object-fit:cover;">
                <?php else: ?>
                  No image
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td>₱<?php echo number_format($item['price'], 2); ?></td>
              <td><?php echo intval($item['quantity']); ?></td>
              <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
              <td>
                <form action="remove_from_cart.php" method="POST">
                  <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
            <td><strong>₱<?php echo number_format($totalAmount, 2); ?></strong></td>
          </tr>
        </tbody>
      </table>
      
      <!-- Checkout Form -->
      <h3>Payment Details</h3>
      <!-- Checkout Form -->
        <form id="checkout-form" action="place_order.php" method="POST">
        <div class="mb-3">
            <label for="mode_of_payment" class="form-label">Mode of Payment</label>
            <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
            <option value="GCash">GCash</option>
            <option value="Paymaya">Paymaya</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount_pay" class="form-label">Amount Pay</label>
            <input type="number" step="0.01" name="amount_pay" id="amount_pay" class="form-control" value="<?php echo htmlspecialchars($totalAmount); ?>" required>
        </div>
        <div class="mb-3">
            <label for="gref" class="form-label">Reference Number (GCash/Paymaya)</label>
            <input type="text" name="gref" id="gref" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="gnumber" class="form-label">Phone Number (GCash/Paymaya)</label>
            <input type="text" name="gnumber" id="gnumber" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Place Order</button>
        </form>

    <?php endif; ?>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $("#checkout-form").submit(function(e) {
      e.preventDefault(); // Prevent normal form submission
      $.ajax({
        type: "POST",
        url: "place_order.php",
        data: ₱(this).serialize(),
        dataType: "json",
        success: function(response) {
          if(response.status === "success"){
            Swal.fire({
              icon: "success",
              title: "Order Placed!",
              text: response.message,
              showConfirmButton: false,
              timer: 3000
            }).then(() => {
              window.location.href = "checkout.php";
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: response.message
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: "error",
            title: "Oops",
            text: "An error occurred while placing your order."
          });
        }
      });
    });
  </script>
</body>
</html>
