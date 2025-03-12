
<?php
    header("location: index.php");

    // Include the database connection file
    include '../assets/package/db.php';

    // Check if the form is submitted
        // Get the form data
        $username = "admin";
        $email = 'admin';
        $password = 'admin123';  // Plain text password

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hashing the password

        // Prepare the SQL query to insert the admin data
        $sql = "INSERT INTO admin (username, email, password, created_at) 
                VALUES (?, ?, ?, NOW())";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New admin added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the query: " . $conn->error;
        }

    // Close the connection
    $conn->close();    
?>