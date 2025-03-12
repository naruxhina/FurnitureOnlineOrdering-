<?php
session_start();
include '../assets/package/db.php'; // Include your database connection

// Fetch all categories
$categories = mysqli_query($conn, "SELECT * FROM category");

// Get selected category_id from URL (default: 0, which means show all products)
$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Fetch products of the selected category or all if no category is selected
$query = "SELECT p.*, p.product_image 
          FROM product p 
          WHERE ($selected_category = 0 OR p.category_id = $selected_category)";
$products = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Furniture</title>
  <link rel="icon" type="image/x-icon" href="icon/favicon.ico"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .category-buttons {
      margin-bottom: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .category-buttons .btn {
      flex-grow: 1;
    }
    .card {
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .btn-grey-active {
      background-color: #c71414 !important;
      color: #fff !important;
    }
    .btn-grey-inactive {
      background-color: #ccc !important;
      color: #000 !important;
      border: none;
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    .card-text.description {
      max-height: 60px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .btn-container {
      display: flex;
      justify-content: space-between;
      margin-top: auto;
    }
    .btn-cart {
      background-color: #ffc107;
      color: #000;
      border: none;
    }
    .btn-cart:hover {
      background-color: #e0a800; /* Darker yellow */
      color: #000;
    }
    .btn-buy {
      background-color: #28a745;
      color: #fff;
      border: none;
    }
    .btn-buy:hover {
      background-color: #1e7e34; /* Darker green */
      color: #fff;
    }
  </style>
</head>
<body>
  <?php include "uploads/header.php"; ?>
  
  <div class="container my-4">
    <!-- Categories Section -->
    <div class="text-center">
      <h3 class="mb-3">Categories</h3>
      <div class="category-buttons">
        <a href="?category_id=0" class="btn <?= ($selected_category == 0) ? 'btn-grey-active' : 'btn-grey-inactive'; ?>">All</a>
        <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
          <a href="?category_id=<?= $row['category_id']; ?>" 
             class="btn <?= ($row['category_id'] == $selected_category) ? 'btn-grey-active' : 'btn-grey-inactive'; ?>">
            <?= htmlspecialchars($row['category_name']); ?>
          </a>
        <?php endwhile; ?>
      </div>
    </div>

    <!-- Products Section -->
    <h3 class="text-left mb-4">Products</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php if (mysqli_num_rows($products) > 0): ?>
        <?php while ($product = mysqli_fetch_assoc($products)) : ?>
          <div class="col">
            <div class="card h-100 shadow-sm">
              <!-- Wrap image in an anchor that triggers the modal -->
              <a href="#" data-bs-toggle="modal" data-bs-target="#imgModal<?= $product['product_id']; ?>">
                <img src="<?= !empty($product['product_image']) ? htmlspecialchars($product['product_image']) : 'default-image.jpg'; ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($product['product_name']); ?>">
              </a>
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['product_name']); ?></h5>
                <p class="card-text"><strong>Price:</strong> ₱<?= number_format($product['price'], 2); ?></p>
                <p class="card-text"><strong>Status:</strong>
                  <span class="badge bg-<?= $product['status'] === 'available' ? 'success' : 'danger'; ?>">
                    <?= ucfirst(htmlspecialchars($product['status'])); ?>
                  </span>
                </p>
                <p class="card-text description"><strong>Description: </strong><?= nl2br(htmlspecialchars($product['description'])); ?></p>
                <!-- Buttons -->
                <div class="btn-container">
                  <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                    <button type="button" class="btn btn-cart add-to-cart" data-product-id="<?= $product['product_id']; ?>">Add to Cart</button>
                  </form>
                  <form action="checkout.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                    <button type="submit" class="btn btn-buy">Buy Now</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <p class="text-muted text-center">No products found.</p>
        </div>
      <?php endif; ?>
    </div>

    <div class="mt-4">
      <?php include "uploads/footer.php"; ?>
    </div>
  </div>
  
  <!-- Generate a modal for each product with an image -->
  <?php 
  // Reset pointer so we can loop through products again if needed.
  mysqli_data_seek($products, 0);
  while ($product = mysqli_fetch_assoc($products)) : 
      if (!empty($product['product_image'])):
  ?>
    <div class="modal fade" id="imgModal<?= $product['product_id']; ?>" tabindex="-1" aria-labelledby="imgModalLabel<?= $product['product_id']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imgModalLabel<?= $product['product_id']; ?>">Product Image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  <?php 
      endif;
  endwhile;
  ?>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- SweetAlert2 -->  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    $(document).ready(function() {
      $(".add-to-cart").click(function(e) {
        e.preventDefault(); // Prevent default form submission
        var productId = $(this).data("product-id");
        
        $.ajax({
          type: "POST",
          url: "cart.php",
          data: { product_id: productId },
          dataType: "json",
          success: function(response) {
            if (response.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Added to Cart!",
                text: response.message,
                showConfirmButton: false,
                timer: 3000
              }).then(() => {
                window.location.href = "furniture.php"; // Redirect back
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message
              });
            }
          },
          error: function() {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Something went wrong!"
            });
          }
        });
      });
    });
  </script>
</body>
</html>
