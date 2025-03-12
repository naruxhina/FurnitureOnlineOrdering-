<?php
include 'assets/package/db.php'; // Include the database connection file

$message = ""; // Variable to store the message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Check if email already exists
    $checkEmail = "SELECT user_id FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Email already registered!</div>";
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Registration successful! <a href='index.php'>Login here</a></div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Japan-Surplus</title>
    <link href="styles.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="icon" type="image/x-icon" href="assets/icon/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-image: url('assets/picture/bg.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: rgba(255, 255, 255, 255);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            height: auto;
            text-align: center;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-register {
            border-radius: 5px;
            width: 30%;
        }
        .title {
            font-size: 50px;
            margin-bottom: 40px!important;
        }
        .login-link {
            display: flex;
            justify-content: flex-end;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2 class="mb-4 title text-primary fw-bold">Registration</h2>
        
        <!-- Display success or error message -->
        <?php if (!empty($message)) echo $message; ?>

        <form action="registration.php" method="POST">
            <div class="mb-3 text-start">
                <label for="name" class="form-label">Name: </label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email: </label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password: </label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="login-link">
                <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
            <button type="submit" class="btn btn-primary btn-register mt-3">Register</button>
        </form>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
