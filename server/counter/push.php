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


$stmt = $conn->prepare("SELECT * FROM receipt WHERE user_id_fk = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$receipt_result = $stmt->get_result();

if ($receipt_result->num_rows === 0) {
    $stmt->close();
    header("Location: ../../client/pages/counter.php");
    exit();
}

$receipt_items = $receipt_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sale_date = date('Y-m-d H:i:s');


$conn->begin_transaction();

try {
    $insert_stmt = $conn->prepare("
        INSERT INTO sales (sale_date, total_sales, total_items, total_cost, user_id_fk, item_name)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $update_stmt = $conn->prepare("
        UPDATE items
        SET product_quantity = product_quantity - ?
        WHERE product_id = ? AND user_id_fk = ?
    ");

    $cost_stmt = $conn->prepare("
        SELECT product_cost FROM items WHERE product_id = ? AND user_id_fk = ?
    ");

    foreach ($receipt_items as $item) {
        $item_id  = sanitize_int($item['item_id_fk']);
        $quantity = sanitize_int($item['item_quantity_fk']);
        $price    = sanitize_positive_number($item['item_price_fk']);
        $name     = sanitize_text($item['item_name_fk']);

        if ($item_id === false || $quantity === false || $price === false) {
            throw new Exception("Invalid receipt data for item.");
        }

        
        $cost_stmt->bind_param("ii", $item_id, $user_id);
        $cost_stmt->execute();
        $cost_row  = $cost_stmt->get_result()->fetch_assoc();
        $item_cost = sanitize_positive_number($cost_row['product_cost'] ?? 0);
        if ($item_cost === false) $item_cost = 0.0;

        $total_sales = $price * $quantity;
        $total_cost  = $item_cost * $quantity;

        
        $insert_stmt->bind_param("sddids", $sale_date, $total_sales, $quantity, $total_cost, $user_id, $name);
        if (!$insert_stmt->execute()) {
            throw new Exception("Insert into sales failed.");
        }

        
        $update_stmt->bind_param("iii", $quantity, $item_id, $user_id);
        if (!$update_stmt->execute()) {
            throw new Exception("Stock update failed.");
        }
    }

    
    $delete_stmt = $conn->prepare("DELETE FROM receipt WHERE user_id_fk = ?");
    $delete_stmt->bind_param("i", $user_id);
    if (!$delete_stmt->execute()) {
        throw new Exception("Receipt clear failed.");
    }
    $delete_stmt->close();

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    
    error_log("push.php error (user $user_id): " . $e->getMessage());
    redirect_with_msg('../../client/pages/counter.php', 'Something went wrong. Please try again.');
}

$insert_stmt->close();
$update_stmt->close();
$cost_stmt->close();

header("Location: ../../client/pages/counter.php");
exit();