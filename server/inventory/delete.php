<?php

session_start();
require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../utils/sanitize.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../client/auth/login.php");
    exit();
}

$user_id = sanitize_int($_SESSION['user_id']);
if ($user_id === false) {
    session_destroy();
    header("Location: ../../client/auth/login.php");
    exit();
}

$product_id = sanitize_int($_POST['id'] ?? '');

if ($product_id === false || $product_id <= 0) {
    redirect_with_msg('../../client/pages/dashboard.php', 'Invalid item ID.');
}

$stmt = $conn->prepare("DELETE FROM items WHERE product_id = ? AND user_id_fk = ?");
$stmt->bind_param("ii", $product_id, $user_id);

if (!$stmt->execute()) {
    $stmt->close();
    error_log("delete.php: failed for product_id=$product_id user_id=$user_id");
    redirect_with_msg('../../client/pages/dashboard.php', 'Delete failed. Please try again.');
}

$stmt->close();
header("Location: ../../client/pages/dashboard.php");
exit();