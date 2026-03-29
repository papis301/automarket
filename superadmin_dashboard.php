<?php
session_start();
require_once 'db.php';

// 🔒 accès super admin uniquement
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    exit("Accès refusé");
}

// 📊 stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// 👥 liste admins
$stmt = $pdo->query("SELECT id, username, phone FROM users WHERE role='admin'");
$admins = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Super Admin Dashboard</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
body { background:#f5f5f5; }
.card { border-radius:10px; }
</style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👑 Super Admin Dashboard</h2>

        <div>
            <a href="create_admin.php" class="btn btn-success">➕ Créer Admin</a>
            <a href="admin_dashboard.php" class="btn btn-primary">Dashboard Admin</a>
            <a href="index.php" class="btn btn-dark">Accueil</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- STATS -->
    <div class="row text-center mb-4">

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h3><?= $totalUsers ?></h3>
                <p>👥 Utilisateurs</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h3><?= $totalAdmins ?></h3>
                <p>🛠️ Admins</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h3><?= $totalProducts ?></h3>
                <p>📦 Produits</p>
            </div>
        </div>

    </div>

    <!-- LISTE ADMINS -->
    <h4>🛠️ Liste des admins</h4>

    <table class="table table-bordered bg-white">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($admins as $admin): ?>
            <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= htmlspecialchars($admin['username']) ?></td>
                <td><?= htmlspecialchars($admin['phone']) ?></td>

                <td>
                    <a href="delete_user.php?id=<?= $admin['id'] ?>&role=admin"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer cet admin ?')">
                       Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

</body>
</html>