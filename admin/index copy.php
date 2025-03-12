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

// Update payment method and status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];

    $stmt = $conn->prepare("UPDATE payment SET payment_method = ?, payment_status = ? WHERE payment_id = ?");
    $stmt->bind_param("ssi", $payment_method, $payment_status, $payment_id);
    if ($stmt->execute()) {
        echo "Payment updated successfully!";
    } else {
        echo "Error updating payment: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch payments with joins
$sql = "
    SELECT p.payment_id, u.user_name, o.order_id, pr.product_name, 
           p.amount_paid, p.payment_method, p.payment_status
    FROM payment p
    JOIN users u ON p.user_id = u.user_id
    JOIN orders o ON p.order_id = o.order_id
    JOIN product pr ON p.product_id = pr.product_id";
$payments = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Payment Management</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="upload/css/styles.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php
        include "upload/navigation.php";
        ?>
        <div id="page-content-wrapper">
            <?php
            include "upload/header.php";
            ?>
            <div class="container-fluid">
                <table border="1">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>User Name</th>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Amount Paid</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $payments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['payment_id']; ?></td>
                                <td><?php echo $row['user_name']; ?></td>
                                <td><?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['amount_paid']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                        <select name="payment_method">
                                            <option value="walk-in" <?php if ($row['payment_method'] == 'walk-in') echo 'selected'; ?>>Walk-in</option>
                                            <option value="online" <?php if ($row['payment_method'] == 'online') echo 'selected'; ?>>Online</option>
                                        </select>
                                </td>
                                <td>
                                    <select name="payment_status">
                                        <option value="paid" <?php if ($row['payment_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                                        <option value="unpaid" <?php if ($row['payment_status'] == 'unpaid') echo 'selected'; ?>>Unpaid</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="upload/js/scripts.js"></script>
</body>

</html>

<?php $conn->close(); ?>