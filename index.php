<?php
    session_start();
    include 'assets/package/db.php'; 

    // Redirect if user is already logged in
    if (isset($_SESSION['user_id'])) {
        header("Location: user/index.php");
        exit();
    }else if(isset($_SESSION['admin_id'])){
        header("Location: admin/index.php");
        exit();
    }
    

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (!empty($email) && !empty($password)) {
            // Check if the user is an admin first
            $query = "SELECT id, email, password FROM admin WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $admin = mysqli_fetch_assoc($result);

                // Verify hashed password for admin
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    
                    header("Location: admin/index.php");
                    exit();
                }
            }

            // If not an admin, check for regular user
            $query = "SELECT user_id, user_email, user_password FROM users WHERE user_email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Verify hashed password for user
                if (password_verify($password, $user['user_password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_email'] = $user['user_email'];
                    
                    header("Location: user/index.php");
                    exit();
                } else {
                    $error = "Invalid email or password!";
                }
            } else {
                $error = "Invalid email or password!";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Please fill in all fields!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Japan-Surplus</title>
    <link href="styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/icon/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-image: url('assets/picture/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: rgba(255, 255, 255, 1);
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
        .btn-login {
            border-radius: 5px;
            width: 30%;
        }
        .title {
            font-size: 50px;
            margin-bottom: 40px !important;
        }
        .signup-link {
            display: flex;
            justify-content: flex-end;
            font-size: 14px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 class="mb-4 title text-primary fw-bold">Welcome!</h2>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email: </label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password: </label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="signup-link">
                <p>Don't have an account? <a href="registration.php">Sign up</a></p>
            </div>
            <button type="submit" class="btn btn-primary btn-login mt-3">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
