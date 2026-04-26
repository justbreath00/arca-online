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

if (!isset($_POST['add'])) {
    header("Location: ../../client/pages/dashboard.php");
    exit();
}


$name     = sanitize_text($_POST['item']     ?? '');
$category = sanitize_text($_POST['category'] ?? '');
$quantity = sanitize_int($_POST['quantity']  ?? '');
$cost     = sanitize_positive_number($_POST['cost']  ?? '');
$price    = sanitize_positive_number($_POST['price'] ?? '');

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
    INSERT INTO items (user_id_fk, product_name, product_category, product_quantity, product_cost, product_price)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("issidd", $user_id, $name, $category, $quantity, $cost, $price);

if (!$stmt->execute()) {
    $stmt->close();
    error_log("add.php: insert failed for user_id=$user_id name=$name");
    redirect_with_msg('../../client/pages/dashboard.php', 'Failed to add item. Please try again.');
}

$stmt->close();
header("Location: ../../client/pages/dashboard.php");
exit();