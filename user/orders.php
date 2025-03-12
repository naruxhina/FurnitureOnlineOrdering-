<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../assets/package/db.php';

$user_id = $_SESSION['user_id'];

// Prepare a statement to fetch orders for this user
$stmt = $conn->prepare("SELECT order_id, product_id, product_name, quantity, order_date, status, mode_of_payment, amount_pay, gnumber, product_image, address FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while($row = $result->fetch_assoc()){
    $orders[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders</title>
   <link rel="icon" type="image/x-icon" href="icon/favicon.ico"/>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <style>
      .order-img {
         width: 50px;
         height: 50px;
         object-fit: cover;
      }
   </style>
</head>
<body>
   <!-- Include Header -->
   <?php include "uploads/header.php"; ?>
   
   <div class="container mt-4">
      <h1 class="mb-4">My Orders</h1>
      <?php if(count($orders) == 0): ?>
         <div class="alert alert-info">You have no orders.</div>
         <a href="furniture.php" class="btn btn-primary">Continue Shopping</a>
      <?php else: ?>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Order ID</th>
                  <th>Product Image</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Order Date</th>
                  <th>Status</th>
                  <th>Payment Method</th>
                  <th>Amount Paid</th>
                  <th>Phone Number</th>
                  <th>Address</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach($orders as $order): ?>
                  <tr>
                     <td><?php echo $order['order_id']; ?></td>
                     <td>
                        <?php if(!empty($order['product_image'])): ?>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#imgModal<?php echo $order['order_id']; ?>">
                              <img src="<?php echo htmlspecialchars($order['product_image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="order-img">
                           </a>
                           <!-- Modal for larger image view -->
                           <div class="modal fade" id="imgModal<?php echo $order['order_id']; ?>" tabindex="-1" aria-labelledby="imgModalLabel<?php echo $order['order_id']; ?>" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="imgModalLabel<?php echo $order['order_id']; ?>">Product Image</h5>
                                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                       <img src="<?php echo htmlspecialchars($order['product_image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="img-fluid">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php else: ?>
                           No image
                        <?php endif; ?>
                     </td>
                     <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                     <td><?php echo $order['quantity']; ?></td>
                     <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                     <td>
                        <?php 
                           // Determine badge class based on status value (case insensitive)
                           $status_val = strtolower($order['status']);
                           if ($status_val == 'pending') {
                              $badge_class = 'bg-secondary';
                           } elseif ($status_val == 'ready to ship') {
                              $badge_class = 'bg-warning';
                           } elseif ($status_val == 'delivered') {
                              $badge_class = 'bg-success';
                           } elseif ($status_val == 'cancelled') {
                              $badge_class = 'bg-danger';
                           } else {
                              $badge_class = 'bg-info';
                           }
                        ?>
                        <span class="badge <?php echo $badge_class; ?>">
                           <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                     </td>
                     <td><?php echo htmlspecialchars($order['mode_of_payment']); ?></td>
                     <td>$<?php echo number_format($order['amount_pay'], 2); ?></td>
                     <td><?php echo htmlspecialchars($order['gnumber']); ?></td>
                     <td><?php echo htmlspecialchars($order['address']); ?></td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      <?php endif; ?>
   </div>
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
