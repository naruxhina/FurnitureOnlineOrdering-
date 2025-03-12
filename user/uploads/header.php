<?php
include '../assets/package/db.php'; 

// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch user details
$user_id = $_SESSION['user_id'] ?? null; // Prevents undefined index error

if ($user_id) {
    $query = "SELECT user_name, user_email, full_address, contact_number FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $address, $contact);
    $stmt->fetch();
    $stmt->close();
} else {
    // Handle case where user is not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header with Cart Modal</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; }
    .header { display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; }
    .logo { display: flex; align-items: center; font-size: 22px; font-weight: bold; gap: 10px; }
    .logo img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
    .nav-links { display: flex; gap: 50px; }
    .nav-links a { color: black; text-decoration: none; font-size: 16px; position: relative; padding-bottom: 5px; font-weight: bold; }
    .nav-links a:hover { color: orange; }
    .icons { position: relative; display: flex; gap: 20px; }
    .icons i { font-size: 22px; cursor: pointer; }
    .user-dropdown { position: absolute; top: 40px; right: 0; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; display: none; flex-direction: column; width: 180px; padding: 10px; }
    .user-dropdown a { text-decoration: none; color: black; padding: 8px; display: block; }
    .user-dropdown a:hover { background: lightgray; }
    .user-dropdown hr { margin: 5px 0; border: 0.5px solid #ddd; }

    /* Modal Styling */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 20px; width: 350px; border-radius: 10px; text-align: center; position: relative; }
    .modal input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; }
    .modal button { background-color: grey; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; }
    .modal button:hover { background-color: green; }
    .close { position: absolute; right: 10px; top: 10px; cursor: pointer; font-size: 20px; }

    /* Cart Modal Specific Styling */
    #cart-modal .modal-content { width: 400px; }
    .cart-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ddd; }
    .cart-item img { width: 50px; height: 50px; object-fit: cover; margin-right: 10px; }
    .cart-item span { flex-grow: 1; }
  </style>
</head>
<body>

<header class="header">
  <div class="logo">
    <a href="index.php" class="fw-bold" style="text-decoration: none; color: black;">
      <img src="../assets/picture/logo.png" alt="Logo"> 
      Japan Surplus Malasiqui
    </a>
  </div>
  <nav class="nav-links">
    <a href="index.php">Home</a>
    <a href="furniture.php">Furnitures</a>
    <a href="Checkout.php">Checkout</a>
    <a href="orders.php">Orders</a>
    <a href="about.php">About Us</a>
  </nav>
  <div class="icons">
    <!-- Cart Icon with an id for toggling the modal -->
    <i class="fas fa-shopping-cart" id="cart-icon"></i>
    <i class="fas fa-user" id="user-icon"></i>

    <!-- User Dropdown Menu -->
    <div class="user-dropdown" id="user-dropdown">
      <p style="text-align: center; font-weight: bold;">👤 <?php echo htmlspecialchars($username); ?></p>
      <hr>
      <a href="#" id="edit-profile-btn">✏️ Edit Profile</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
</header>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="modal">
  <div class="modal-content">
    <span class="close" id="close-modal">&times;</span>
    <h2>Edit Profile</h2>
    <form id="edit-profile-form">
      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
      <input type="text" name="user_name" placeholder="Name" value="<?php echo htmlspecialchars($username); ?>" required>
      <input type="email" name="user_email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
      <input type="text" name="full_address" placeholder="Full Address" value="<?php echo htmlspecialchars($address); ?>" required>
      <input type="text" name="contact_number" placeholder="Contact Number" value="<?php echo htmlspecialchars($contact); ?>" required>
      <input type="password" name="user_password" placeholder="New Password (Leave blank if not changing)">
      <button type="submit">Update Profile</button>
    </form>
  </div>
</div>

<!-- Cart Modal -->
<div id="cart-modal" class="modal">
  <div class="modal-content">
    <span class="close" id="close-cart">&times;</span>
    <h2>Your Cart</h2>
    <div id="cart-items">
      <?php 
      $cart_items = $_SESSION['cart'] ?? [];
      if (empty($cart_items)) {
          echo "<p>Your cart is empty.</p>";
      } else {
          foreach ($cart_items as $item) {
              echo '<div class="cart-item">';
              // Display product image if available:
              if (!empty($item['product_image'])) {
                  echo '<img src="' . htmlspecialchars($item['product_image']) . '" alt="' . htmlspecialchars($item['product_name']) . '">';
              }
              echo '<span>' . htmlspecialchars($item['product_name']) . ' (Qty: ' . intval($item['quantity']) . ')</span>';
              ?>
              <form action="remove_from_cart.php" method="POST" style="display:inline-block;">
                  <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Remove</button>
              </form>
              <?php
              echo '</div>';
          }
      }
      ?>
    </div>
    <div class="mt-3 text-end">
      <a href="checkout.php" class="btn btn-success">Checkout</a>
    </div>
  </div>
</div>

<script>
  // Edit Profile Modal Handlers
  document.getElementById('edit-profile-btn').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('edit-profile-modal').style.display = 'flex';
  });
  document.getElementById('close-modal').addEventListener('click', function() {
      document.getElementById('edit-profile-modal').style.display = 'none';
  });
  document.addEventListener('click', function(event) {
      var modal = document.getElementById('edit-profile-modal');
      if (event.target === modal) {
          modal.style.display = 'none';
      }
  });

  // AJAX for Edit Profile Form Submission
  $(document).ready(function() {
      $("#edit-profile-form").submit(function(e) {
          e.preventDefault();
          $.ajax({
              type: "POST",
              url: "update_profile.php",
              data: $(this).serialize(),
              success: function(response) {
                  alert(response);
                  location.reload();
              }
          });
      });
  });

  // User Dropdown Handlers
  $(document).ready(function() {
      $("#user-icon").click(function(event) {
          event.stopPropagation();
          $("#user-dropdown").toggle();
      });
      $(document).click(function(event) {
          if (!$(event.target).closest("#user-icon, #user-dropdown").length) {
              $("#user-dropdown").hide();
          }
      });
  });

  // Cart Modal Handlers
  $(document).ready(function() {
      $("#cart-icon").click(function(event) {
          event.stopPropagation();
          $("#cart-modal").css("display", "flex");
      });
      $("#close-cart").click(function() {
          $("#cart-modal").css("display", "none");
      });
      $(document).click(function(event) {
          if ($(event.target).is("#cart-modal")) {
              $("#cart-modal").css("display", "none");
          }
      });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
