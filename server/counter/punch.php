<?php

session_start();
require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../utils/sanitize.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../client/auth/login.php");
    exit();
}

$user_id = sanitize_int($_SESSION['user_id']);
$item_id = sanitize_int($_POST['id'] ?? '');

if ($user_id === false || $item_id === false) {
    redirect_with_msg('../../client/pages/counter.php', 'Invalid request.');
}

// --- Verify the item belongs to this user ---
$stmt = $conn->prepare("
    SELECT product_id, product_name, product_price, product_quantity
    FROM items
    WHERE product_id = ? AND user_id_fk = ?
");
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$item) {
    redirect_with_msg('../../client/pages/counter.php', 'Item not found.');
}

// --- Check how many are already sitting in the receipt (not yet pushed) ---
$stmt = $conn->prepare("
    SELECT item_quantity_fk
    FROM receipt
    WHERE user_id_fk = ? AND item_id_fk = ?
");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

$in_receipt = $existing ? (int)$existing['item_quantity_fk'] : 0;

// --- Real available stock = DB quantity minus what's already staged in the receipt ---
$available = (int)$item['product_quantity'] - $in_receipt;

if ($available <= 0) {
    redirect_with_msg('../../client/pages/counter.php', 'Not enough stock for ' . htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') . '.');
}

// --- Add to receipt or increment ---
if ($existing) {
    $stmt = $conn->prepare("
        UPDATE receipt
        SET item_quantity_fk = item_quantity_fk + 1
        WHERE user_id_fk = ? AND item_id_fk = ?
    ");
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $stmt->close();
} else {
    $name  = sanitize_text($item['product_name']);
    $price = (float)$item['product_price'];

    $stmt = $conn->prepare("
        INSERT INTO receipt (user_id_fk, item_id_fk, item_name_fk, item_price_fk, item_quantity_fk)
        VALUES (?, ?, ?, ?, 1)
    ");
    $stmt->bind_param("iisd", $user_id, $item_id, $name, $price);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../../client/pages/counter.php");
exit();