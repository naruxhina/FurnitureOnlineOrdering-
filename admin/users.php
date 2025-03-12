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

// Fetch users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>User Management</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="upload/assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="upload/css/styles.css" rel="stylesheet" />
</head>
<style>
        body {
            background-color: #f8f9fa;
        }
        .crm-table {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #dc3545;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
<body>
    <div class="d-flex" id="wrapper">
        <?php
        include "upload/navigation.php";
        ?>
        <div id="page-content-wrapper">
            <?php
            include "upload/header.php";
            ?>
            <div class="container mt-5">
    <div class="crm-table p-4">
        <h2 class="text-danger mb-4 fw-bold text-center">User Management</h2>
        <table class="table table-bordered table-hover">
            <thead style="
    background-color: black;
">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Full Address</th>
                    <th>Contact Number</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['user_email']; ?></td>
                        <td><?php echo $row['full_address']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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