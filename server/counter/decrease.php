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


$stmt = $conn->prepare("
    SELECT receipt_id, item_quantity_fk
    FROM receipt
    WHERE user_id_fk = ? AND item_id_fk = ?
");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$existing) {
    
    header("Location: ../../client/pages/counter.php");
    exit();
}

if ($existing['item_quantity_fk'] > 1) {
    $stmt = $conn->prepare("
        UPDATE receipt
        SET item_quantity_fk = item_quantity_fk - 1
        WHERE user_id_fk = ? AND item_id_fk = ?
    ");
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("
        DELETE FROM receipt
        WHERE user_id_fk = ? AND item_id_fk = ?
    ");
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../../client/pages/counter.php");
exit();