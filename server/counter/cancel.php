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

$stmt = $conn->prepare("DELETE FROM receipt WHERE user_id_fk = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header("Location: ../../client/pages/counter.php");
exit();