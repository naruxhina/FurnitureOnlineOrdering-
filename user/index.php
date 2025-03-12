<?php
session_start();
include '../assets/package/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Use product_image column directly from the product table
$query = "SELECT p.*, p.product_image FROM product p ORDER BY p.quantity DESC LIMIT 6";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap and FontAwesome Example</title>
  <!-- Bootstrap CSS -->
  <link rel="icon" type="image/x-icon" href="icon/favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    .content1 {
      background: url('../assets/picture/homebg.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      padding: 20px;
      margin: 0 20px !important;
    }
    .content-wrapper {
      position: relative;
      z-index: 2;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 90%;
    }
    .content-wrapper h1 {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .content-wrapper p {
      font-size: 1.5rem;
      margin-bottom: 30px;
    }
    .btn-primary {
      background-color: #ff5a5f;
      border: none;
      padding: 12px 25px;
      font-size: 1.1rem;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    .btn-primary:hover {
      background-color: #e84f52;
    }
    .content2 .rounded {
      width: 80%;
      max-height: 250px;
      border: 2px solid black;
      margin: 30px 20px;
      border-radius: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .content2 .rounded:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }
    .use_image {
      border: solid 1px black;
      width: 250px;
      height: 400px !important;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: black;
      border-radius: 50%;
      padding: 10px;
    }
  </style>
</head>
<body>
  <?php include 'uploads/header.php'; ?>

  <div class="content1">
    <div class="content-wrapper">
      <h1>Transform Your Space on a Budget!</h1>
      <p>Discover unique, quality second-hand furniture<br> and find your perfect piece today!</p>
    </div>
  </div>

  <div class="content2 ms-5 me-5">
    <div class="row">
      <div class="col-md-3">
        <h1 class="fw-bold mt-3">Shop by Categories</h1>
        <!-- Cart Icon  -->
        <div class="d-flex">
          <i class="fas fa-shopping-cart fa-3x me-2"></i>
          <p>200+ <br>unique products</p>
        </div>
        <img src="../assets/picture/use_image.jpg" class="use_image ms-5 mt-2" alt="Image 1">
      </div>
      <div class="col-md-9">
        <div class="row">
          <?php while ($row = mysqli_fetch_assoc($result)) { 
            // Get the product image from the product table
            $image = $row['product_image'];
          ?>
          <div class="col-md-4">
            <!-- When clicking the image, redirect to furniture.php. Optionally pass product id -->
            <a href="furniture.php?product_id=<?php echo $row['product_id']; ?>">
              <img src="<?php echo htmlspecialchars($image); ?>" class="img-fluid rounded" alt="Product Image">
            </a>
          </div>
          <?php } ?>
        </div>
        <!-- Include Bootstrap JS (Only Once) --> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      </div>
    </div>
  </div>

  <?php include 'uploads/footer.php'; ?>

  <!-- Bootstrap JS and Popper.js (if not already included) --> 
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
