<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="icon" type="image/x-icon" href="icon/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .full {
            display: flex;
            flex-direction: column;
            height: 100vh; /* Ensure the full container is exactly 100vh */
        }

        .content {
            flex-grow: 1; /* Takes up remaining space between header and footer */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .about_footer {
            color: white;
            text-align: center;
            width: 100%;
        }

        .img img {
            max-width: 100%;
            max-height: 80vh; /* Prevents the image from overflowing */
            height: auto;
            border-radius: 10px;
            display: block;
            margin: auto;
        }

        .text-content {
            text-align: left;
        }

        .txt {
            font-size: 25px;
        }
    </style>
</head>
<body>

    <div class="full">
        <?php include "uploads/header.php"; ?>

        <div class="content container">
            <div class="row align-items-center">
                <div class="txt col-md-6 text-content">
                    <h2 class="fw-bold">About Us</h2>
                    <p>Welcome to Japan Surplus, your trusted source for quality secondhand furniture in Malasiqui, Pangasinan, Philippines. We're passionate about giving new life to gently used furniture, reducing waste, and making sustainable living affordable.</p>
                    <p>Japan Surplus was born from a desire to create a more circular economy in the furniture industry. We believe everyone deserves access to comfortable, stylish, and eco-friendly furniture without breaking the bank.</p>
                </div>
                <div class="img col-md-6">
                    <img src="../assets/picture/use_image.jpg" alt="About Us">
                </div>
            </div>
        </div>

        <div class="about_footer">
            <?php include "uploads/footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
