<?php
include '../assets/package/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $new_username = $_POST['user_name'];
    $new_email = $_POST['user_email'];
    $new_address = $_POST['full_address'];
    $new_contact = $_POST['contact_number'];

    if (!empty($_POST['user_password'])) {
        $new_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
        $query = "UPDATE users SET user_name=?, user_email=?, full_address=?, contact_number=?, user_password=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $new_username, $new_email, $new_address, $new_contact, $new_password, $user_id);
    } else {
        $query = "UPDATE users SET user_name=?, user_email=?, full_address=?, contact_number=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $new_username, $new_email, $new_address, $new_contact, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile.";
    }
}
?>
