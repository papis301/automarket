<?php
session_start();
require_once 'db.php';

// Récupérer tous les produits + image principale
$stmt = $pdo->query("
    SELECT 
        p.id, 
        p.title, 
        p.price,
        (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
    FROM products p
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Produits - Auto Market</title>

    <!-- CSS UREN -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .product-img img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>

<body>

<!-- HEADER SIMPLE -->
<div class="container mt-3 d-flex justify-content-between">
    <h2>AUTO MARKET</h2>
    <a href="index.php" class="btn btn-dark">Accueil</a>
</div>

<div class="container mt-4">

    <h3 class="mb-4">Tous les produits</h3>

    <div class="row">

        <?php if(empty($products)): ?>
            <p>Aucun produit disponible.</p>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                <div class="single-product">

                    <div class="product-img">
                        <a href="product_details.php?id=<?= $p['id'] ?>">
                            <?php if($p['main_image']): ?>
                                <img src="<?= $p['main_image'] ?>">
                            <?php else: ?>
                                <img src="assets/no-image.png">
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="product-content text-center mt-2">
                        <h6>
                            <a href="product_details.php?id=<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['title']) ?>
                            </a>
                        </h6>

                        <span class="text-success">
                            <?= number_format($p['price'], 0, ',', ' ') ?> FCFA
                        </span>
                    </div>

                </div>

            </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>