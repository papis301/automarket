<?php
session_start();
require_once 'db.php';

$image = $_GET['image'] ?? null;

if (!$image) {
    die("Image introuvable");
}

// Récupérer tous les produits (simple pour l'instant)
$stmt = $pdo->query("
    SELECT p.*, 
    (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
    FROM products p
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat recherche</title>

    <!-- CSS UREN -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="container mt-4">

<h2>🔍 Résultat de recherche</h2>

<!-- IMAGE UPLOADÉE -->
<div class="mb-4">
    <h5>Image recherchée :</h5>
    <img src="<?= htmlspecialchars($image) ?>" style="max-width:300px;border-radius:10px">
</div>

<hr>

<h4>Produits disponibles :</h4>

<div class="row">
<?php foreach($products as $p): ?>
    <div class="col-md-3 mb-4">
        <div class="card">

            <?php if($p['main_image']): ?>
                <img src="<?= $p['main_image'] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
            <?php else: ?>
                <img src="no_image.png" class="card-img-top">
            <?php endif; ?>

            <div class="card-body">
                <h6><?= htmlspecialchars($p['title']) ?></h6>
                <p><?= number_format($p['price'],0,',',' ') ?> FCFA</p>

                <a href="product_details.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
                    Voir
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>