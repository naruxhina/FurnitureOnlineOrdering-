<?php 
    include '../assets/package/db.php'; 
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <title>Footer</title>
    <style>
        .footer {
            background-color: red;
            color: white;
            text-align: center;
            width: 100%;
        }
        .footer-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            min-height: 80px; /* Adjust height as needed */
            align-items: center; /* Centers content vertically */
        }
        .footer div {
            margin: 10px;
        }
        .copyright {
            background-color: lightgrey;
            margin: 0 !important;
            width: 100%;
            color: black;
        }
        p{
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="footer">
        <div class="footer-container">
            <div>
                <p><strong>GET IN TOUCH WITH US:</strong></p>
                <p><i class="fa-solid fa-phone"></i> +639150063190</p>
                <p> <i class="fa-solid fa-envelope"></i> Kevinmhay143@gmail.com</p>
            </div>
            <div>
                <p><strong>OTHER BRANCHES</strong></p>
                <p><i class="fa-solid fa-location-dot"></i> Bayambang, Malasiqui, Pangasinan</p>
                <p><i class="fa-solid fa-location-dot"></i> San Carlos, Malasiqui, Pangasinan</p>
                <p><i class="fa-solid fa-location-dot"></i> Montemayor St., Malasiqui, Pangasinan</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 Japan Surplus</p>
        </div>
    </div>

</body>
</html>
