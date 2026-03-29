<?php
session_start();
require_once 'db.php';

// 🔒 Vérifier admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit("Accès refusé");
}

// 📦 Récupérer tous les produits
$stmt = $pdo->query("
    SELECT p.*, u.username, c.name AS category_name,
    (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
    FROM products p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
body { background:#f5f5f5; }
img { height:60px; width:80px; object-fit:cover; }
</style>
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-4">
        <h2>🛠️ Dashboard Admin</h2>

        <div>
            <a href="categories.php" class="btn btn-warning">📂 Catégories</a>
            <a href="index.php" class="btn btn-dark">Accueil</a>
            <?php if($_SESSION['role'] === 'super_admin'): ?>
                <a href="create_admin.php" class="btn btn-success">👑 Créer Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- TABLE PRODUITS -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">

            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Vendeur</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($products as $p): ?>
                <tr>

                    <!-- IMAGE -->
                    <td>
                        <?php if($p['main_image']): ?>
                            <img src="<?= $p['main_image'] ?>">
                        <?php else: ?>
                            <span>Aucune</span>
                        <?php endif; ?>
                    </td>

                    <!-- TITRE -->
                    <td><?= htmlspecialchars($p['title']) ?></td>

                    <!-- PRIX -->
                    <td class="text-success">
                        <?= number_format($p['price'],0,',',' ') ?> FCFA
                    </td>

                    <!-- CATEGORIE -->
                    <td><?= htmlspecialchars($p['category_name'] ?? 'Aucune') ?></td>

                    <!-- VENDEUR -->
                    <td><?= htmlspecialchars($p['username']) ?></td>

                    <!-- DATE -->
                    <td><?= $p['created_at'] ?></td>

                    <!-- ACTIONS -->
                    <td>

                        <a href="product_details.php?id=<?= $p['id'] ?>"
                           class="btn btn-primary btn-sm">
                           Voir
                        </a>

                        <a href="delete_product.php?id=<?= $p['id'] ?>&admin=1"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce produit ?')">
                           Supprimer
                        </a>

                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>