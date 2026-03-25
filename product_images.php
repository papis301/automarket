<?php
require_once 'db.php';

// Vérifier l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    exit("Produit non trouvé.");
}

$product_id = (int) $_GET['id'];

// Récupérer le produit
$stmt = $pdo->prepare("SELECT title FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    exit("Produit introuvable.");
}

// Récupérer les images
$stmt = $pdo->prepare("
    SELECT image_path 
    FROM product_images 
    WHERE product_id = ?
    ORDER BY id DESC
");
$stmt->execute([$product_id]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Images - <?= htmlspecialchars($product['title']) ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <style>
        body { background:#f4f4f4; }
        .img-box {
            background:white;
            padding:10px;
            border-radius:6px;
            box-shadow:0 0 8px rgba(0,0,0,0.1);
        }
        .img-box img {
            width:100%;
            height:200px;
            object-fit:cover;
            border-radius:5px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Images du produit : <?= htmlspecialchars($product['title']) ?></h2>

    <a href="product_details.php?id=<?= $product_id ?>" class="btn btn-dark mb-4">
        ⬅ Retour au produit
    </a>

    <div class="row">
        <?php if (!empty($images)): ?>

            <?php foreach ($images as $img): ?>
                <div class="col-md-3 mb-4">
                    <div class="img-box">
                        <img src="<?= htmlspecialchars($img) ?>" alt="Image produit">
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Aucune image disponible pour ce produit.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>