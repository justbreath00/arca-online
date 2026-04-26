<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../utils/sanitize.php';

if (isset($_POST['signup'])) {

    
    $email    = sanitize_email($_POST['email'] ?? '');
    $username = sanitize_text($_POST['username'] ?? '');
    $password = sanitize_password($_POST['password'] ?? '');
    $confirm  = sanitize_password($_POST['confirm'] ?? '');
    $user_date = date('Y-m-d');
    
    if (!$email) {
        redirect_with_msg('../../client/auth/register.php', 'Invalid email format. Use Gmail, Yahoo, Outlook, or Hotmail.');
    }

    
    if ($username === '') {
        redirect_with_msg('../../client/auth/register.php', 'Username is required.');
    }

    
    if (!is_strong_password($password)) {
        redirect_with_msg('../../client/auth/register.php', 'Password must be at least 8 characters and contain uppercase, lowercase, and a number.');
    }

    
    if ($password !== $confirm) {
        redirect_with_msg('../../client/auth/register.php', 'Passwords do not match.');
    }

    
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_email = ?");
    if (!$stmt) {
        redirect_with_msg('../../client/auth/register.php', 'Server error. Please try again.');
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        redirect_with_msg('../../client/auth/register.php', 'Email is already registered.');
    }
    $stmt->close();

    
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (user_email, user_name, user_password, user_date) VALUES (?, ?, ?,?)");
    if (!$stmt) {
        redirect_with_msg('../../client/auth/register.php', 'Server error. Please try again.');
    }
    $stmt->bind_param("ssss", $email, $username, $hashed_password, $user_date);

    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id;
        $stmt->close();

        $_SESSION['user_id']   = $new_user_id;
        $_SESSION['user_name'] = $username;

        header("Location: ../../client/pages/dashboard.php");
        exit();
    } else {
        $stmt->close();
        redirect_with_msg('../../client/auth/register.php', 'Signup failed. Please try again.');
    }
}
?>