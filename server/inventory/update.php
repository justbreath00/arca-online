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

if (!isset($_POST['edit'])) {
    header("Location: ../../client/pages/dashboard.php");
    exit();
}

$product_id = sanitize_int($_POST['updateId'] ?? '');
$name       = sanitize_text($_POST['item']     ?? '');
$category   = sanitize_text($_POST['category'] ?? '');
$quantity   = sanitize_int($_POST['quantity']  ?? '');
$cost       = sanitize_positive_number($_POST['cost']  ?? '');
$price      = sanitize_positive_number($_POST['price'] ?? '');

if ($product_id === false || $product_id <= 0) {
    redirect_with_msg('../../client/pages/dashboard.php', 'Invalid item ID.');
}
if ($name === '') {
    redirect_with_msg('../../client/pages/dashboard.php', 'Item name is required.');
}
if ($quantity === false || $quantity < 0) {
    redirect_with_msg('../../client/pages/dashboard.php', 'Invalid quantity.');
}
if ($cost === false || $price === false) {
    redirect_with_msg('../../client/pages/dashboard.php', 'Invalid cost or price.');
}


$allowed_categories = ['food', 'drinks', 'canned', 'noodles', 'snacks', 'cleaning', 'others'];
if (!in_array($category, $allowed_categories, true)) {
    redirect_with_msg('../../client/pages/dashboard.php', 'Invalid category.');
}

$stmt = $conn->prepare("
    UPDATE items
    SET product_name     = ?,
        product_category = ?,
        product_quantity = ?,
        product_cost     = ?,
        product_price    = ?
    WHERE product_id = ? AND user_id_fk = ?
");
$stmt->bind_param("ssiddii", $name, $category, $quantity, $cost, $price, $product_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();
    header("Location: ../../client/pages/dashboard.php");
    exit();
} else {
    $stmt->close();
    error_log("update.php: no rows affected for product_id=$product_id user_id=$user_id");
    redirect_with_msg('../../client/pages/dashboard.php', 'Update failed or item not found.');
}