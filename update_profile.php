<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false, 'message' => 'Unknown error occurred.'];

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    $response['message'] = 'You must be logged in to update your profile.';
    echo json_encode($response);
    exit();
}

require 'login/connect.php'; // Include the database connection

// Validate and sanitize input
$id = $_SESSION['user_id'];
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$username || !$email) {
    $response['message'] = 'Invalid input.';
    echo json_encode($response);
    exit();
}

// Update user profile information in the database
$stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
$stmt->bind_param("ssi", $username, $email, $id);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $response['success'] = true;
    $response['message'] = 'Profile updated successfully.';
} else {
    $response['message'] = 'Error updating profile: ' . $stmt->error;
}
$stmt->close();
$conn->close();

echo json_encode($response);

// Redirect after sending JSON response
header("Location: index.php");
exit();
?>
