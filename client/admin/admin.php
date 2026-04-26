<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../server/utils/sanitize.php';
require_once __DIR__ . '/../../server/queries/admin_queries.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$acting_id = sanitize_int($_SESSION['user_id']);
if ($acting_id === false) {
    session_destroy();
    header("Location: ../auth/login.html");
    exit();
}


$stmt = $conn->prepare("SELECT user_name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $acting_id);
$stmt->execute();
$actor = $stmt->get_result()->fetch_assoc();
$stmt->close();


$actor = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
 
if (!$actor || $actor !== 'admin_renier') {
    header("Location: ../pages/dashboard.php");

    exit();
}
$sort  = sanitize_text($_GET['sort'] ?? 'name');
$users = get_all_users($conn, $sort);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arca Admin</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/logo.png">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #0A0A16; color: #fff; min-height: 100vh; }

        header {
            width: 100%; height: 60px; background: #1c1c29;
            display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
        }
        header .logo img { width: 80px; height: auto; }
        header nav { display: flex; gap: 20px; align-items: center; }
        header nav a { color: #fff; text-decoration: none; font-weight: bold; transition: 0.3s; }
        header nav a:hover { color: #00bcd4; }
        header .logout button {
            background: #e74c3c; border: none; color: #fff;
            padding: 6px 12px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        header .logout button:hover { background: #c0392b; }

        main { padding: 20px; width: 84%; margin: 0 auto; }
        h1 { margin-bottom: 20px; }

        .sort-buttons { margin-bottom: 15px; }
        .sort-buttons a {
            text-decoration: none; padding: 6px 12px; margin-right: 5px;
            background: #00bcd4; color: #fff; border-radius: 5px; font-size: 14px; transition: 0.3s;
        }
        .sort-buttons a:hover { background: #0097a7; }
        .sort-buttons a.active { background: #0097a7; }

        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 600px; }
        th { text-align: left; font-weight: normal; padding: 10px 20px; color: #fff; }
        td { padding: 10px 20px; background: #1f1f2e; color: #fff; }
        td:first-child { border-top-left-radius: 50px; border-bottom-left-radius: 50px; }
        td:last-child  { border-top-right-radius: 50px; border-bottom-right-radius: 50px; }
        tbody tr:hover td { background: #333454; }

        .delete-button {
            background: #e74c3c; border: none; color: #fff;
            padding: 6px 12px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        .delete-button:hover { background: #c0392b; }

        .delete-confirm {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: #1c1c29; padding: 20px 30px; border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5); text-align: center; z-index: 1000; display: none;
        }
        .delete-confirm p { margin-bottom: 20px; font-size: 16px; color: #fff; }
        .delete-confirm button {
            padding: 8px 16px; margin: 0 10px; border: none;
            border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        .confirm-yes { background: #e74c3c; color: #fff; }
        .confirm-yes:hover { background: #c0392b; }
        .confirm-no  { background: #95a5a6; color: #fff; }
        .confirm-no:hover  { background: #7f8c8d; }

        @media(max-width:700px) { th, td { padding: 8px 12px; font-size: 14px; } .sort-buttons a { font-size: 12px; padding: 4px 8px; } }
        @media(max-width:500px) { th, td { padding: 6px 8px;  font-size: 12px; } }
    </style>
</head>
<body>

<header>
    <div class="logo"><img src="../assets/img/arca.png" alt="Arca Logo"></div>
    <nav></nav>
    <div class="logout">
        <form action="../index.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
</header>

<main class="table-wrapper">
    <h1>Welcome Admin</h1>

    <div class="sort-buttons">
        <a href="admin.php?sort=name"  <?= $sort === 'name'  ? 'class="active"' : '' ?>>Sort by Name</a>
        <a href="admin.php?sort=date"  <?= $sort === 'date'  ? 'class="active"' : '' ?>>Sort by Date</a>
        <a href="admin.php?sort=sales" <?= $sort === 'sales' ? 'class="active"' : '' ?>>Sort by Sales</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Created Date</th>
                <th>Total Sales</th>
                <th>Last Login</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users): ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['user_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₱<?= number_format($user['total_sales'], 2) ?></td>
                    <td><?= htmlspecialchars($user['last_login'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form class="delete-form" action="../../server/control.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?= (int)$user['user_id'] ?>">
                            <button class="delete-button" type="button">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div class="delete-confirm" id="deleteConfirm">
    <p>Do you want to delete this user?</p>
    <button class="confirm-yes" id="confirmYes">Yes</button>
    <button class="confirm-no"  id="confirmNo">No</button>
</div>

<script>
let selectedForm = null;

document.querySelectorAll('.delete-button').forEach(btn => {
    btn.addEventListener('click', function () {
        selectedForm = this.closest('.delete-form');
        document.getElementById('deleteConfirm').style.display = 'block';
    });
});

document.getElementById('confirmYes').addEventListener('click', function () {
    if (selectedForm) selectedForm.submit();
});

document.getElementById('confirmNo').addEventListener('click', function () {
    document.getElementById('deleteConfirm').style.display = 'none';
    selectedForm = null;
});
</script>

</body>
</html>