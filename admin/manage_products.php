<?php
session_start();
include '../assets/package/db.php';

// (Optional) Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Query products along with their category names
$query = "SELECT p.*, c.category_name 
          FROM product p 
          LEFT JOIN category c ON p.category_id = c.category_id 
          ORDER BY p.product_id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link rel="icon" type="image/x-icon" href="upload/assets/favicon.ico"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .product-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <div class="d-flex" id="wrapper">
    <?php include "uploads/navigation.php"; ?>
    <div id="page-content-wrapper">
      <?php include "uploads/header.php"; ?>
      <div class="container mt-4">
        <h1 class="mb-4">Manage Products</h1>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Category</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Status</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['product_id']; ?></td>
                  <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                  <td>₱<?php echo number_format($row['price'], 2); ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td><?php echo htmlspecialchars($row['status']); ?></td>
                  <td>
                    <?php if (!empty($row['product_image'])): ?>
                      <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="Image" class="product-img">
                    <?php else: ?>
                      No image
                    <?php endif; ?>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-primary edit-btn" data-id="<?php echo $row['product_id']; ?>">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['product_id']; ?>">
                      <i class="fas fa-trash-alt"></i> Delete
                    </button>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">No products found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProductForm">
            <input type="hidden" id="product_id" name="product_id">
            <div class="mb-3">
              <label for="product_name" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">Price</label>
              <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class=" mb-3">
              <label for="status" class="form-label">Status</label>
              <input type="text" class="form-control" id="status" name="status" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const editButtons = document.querySelectorAll('.edit-btn');
      const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));

      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const row = this.closest('tr');
          const productId = row.querySelector('td:nth-child(1)').textContent;
          const productName = row.querySelector('td:nth-child(3)').textContent;
          const price = row.querySelector('td:nth-child(4)').textContent.replace('$', '');
          const quantity = row.querySelector('td:nth-child(5)').textContent;
          const status = row.querySelector('td:nth-child(6)').textContent;

          document.getElementById('product_id').value = productId;
          document.getElementById('product_name').value = productName;
          document.getElementById('price').value = price;
          document.getElementById('quantity').value = quantity;
          document.getElementById('status').value = status;

          editProductModal.show();
        });
      });

      document.getElementById('saveChanges').addEventListener('click', function() {
        const form = document.getElementById('editProductForm');
        const formData = new FormData(form);
        
        fetch('update_product.php', { method: 'POST', body: formData })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              location.reload(); // Reload the page to see the updated data
            } else {
              alert('Error updating product: ' + data.message);
            }
          })
          .catch(error => console.error('Error:', error));

        editProductModal.hide();
      });
    });
  </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');

            if (confirm("Are you sure you want to delete this product?")) {
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});
</script>

</body>
</html>