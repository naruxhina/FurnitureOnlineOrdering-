<?php
include '../assets/package/db.php'; 
session_start();

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT user_name, user_email, full_address, contact_number FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $address, $contact);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['user_name'];
    $new_email = $_POST['user_email'];
    $new_address = $_POST['full_address'];
    $new_contact = $_POST['contact_number'];

    // Optional: Password update
    if (!empty($_POST['user_password'])) {
        $new_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET user_name = ?, user_email = ?, full_address = ?, contact_number = ?, user_password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssi", $new_username, $new_email, $new_address, $new_contact, $new_password, $user_id);
    } else {
        $update_query = "UPDATE users SET user_name = ?, user_email = ?, full_address = ?, contact_number = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $new_username, $new_email, $new_address, $new_contact, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location='edit_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form { display: inline-block; text-align: left; width: 300px; margin-top: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; }
        button { background-color: orange; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background-color: darkorange; }
    </style>
</head>
<body>
    <h2>Edit Profile</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="user_name" value="<?php echo htmlspecialchars($username); ?>" required>

        <label>Email:</label>
        <input type="email" name="user_email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label>Full Address:</label>
        <input type="text" name="full_address" value="<?php echo htmlspecialchars($address); ?>" required>

        <label>Contact Number:</label>
        <input type="text" name="contact_number" value="<?php echo htmlspecialchars($contact); ?>" required>

        <label>New Password (Optional):</label>
        <input type="password" name="user_password">

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
