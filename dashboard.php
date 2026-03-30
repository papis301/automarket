<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer produits + image principale
$stmt = $pdo->prepare("
    SELECT p.*, 
    (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
    FROM products p
    WHERE user_id = ?
    ORDER BY p.id DESC
");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Dashboard</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
body { background:#f5f5f5; }
.card img { height:200px; object-fit:cover; }
</style>
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Mon Dashboard</h2>

        <div>
    <a href="add_product.php" class="btn btn-success">➕ Ajouter produit</a>

    <a href="products.php" class="btn btn-primary">📦 Voir produits</a>

    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin'): ?>
    <a href="categories.php" class="btn btn-warning">📂 Catégories</a>
<?php endif; ?>

    <a href="index.php" class="btn btn-dark">Accueil</a>

    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
    </div>

    <!-- PRODUITS -->
    <div class="row">

        <?php if(empty($products)): ?>
            <div class="alert alert-info">
                Aucun produit ajouté.
            </div>
        <?php endif; ?>

        <?php foreach($products as $p): ?>
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card shadow-sm">

                <!-- IMAGE -->
                <a href="product_details.php?id=<?= $p['id'] ?>">
                    <img src="<?= $p['main_image'] ?? 'assets/no-image.png' ?>">
                </a>

                <!-- CONTENT -->
                <div class="card-body">

                    <h5><?= htmlspecialchars($p['title']) ?></h5>

                    <p class="text-success fw-bold">
                        <?= number_format($p['price'],0,',',' ') ?> FCFA
                    </p>

                    <p class="text-muted">
                        <?= substr(htmlspecialchars($p['description']),0,80) ?>...
                    </p>

                    <!-- ACTIONS -->
                    <div class="d-flex justify-content-between">

                        <a href="edit_product.php?id=<?= $p['id'] ?>"
                           class="btn btn-primary btn-sm">
                           Modifier
                        </a>

                        <a href="delete_product.php?id=<?= $p['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce produit ?')">
                           Supprimer
                        </a>

                    </div>

                </div>

            </div>

        </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>