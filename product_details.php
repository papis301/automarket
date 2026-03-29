<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit("Produit introuvable.");

// Produit + catégorie + vendeur
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name, u.username, u.phone
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) exit("Produit introuvable.");

// Images
$stmtImg = $pdo->prepare("
    SELECT image_path 
    FROM product_images 
    WHERE product_id = ?
");
$stmtImg->execute([$id]);
$images = $stmtImg->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($product['title']) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <style>
        body { background:#f5f5f5; }
        .main-img { height:400px; object-fit:cover; width:100%; }
        .thumb { width:90px; height:70px; object-fit:cover; cursor:pointer; margin-right:5px; }
        .box { background:white; padding:20px; border-radius:8px; }
    </style>

    <script>
        function changeImage(src){
            document.getElementById("mainImage").src = src;
        }
    </script>
</head>

<body>

<div class="container mt-5">

    <a href="index.php" class="btn btn-dark mb-3">⬅ Retour</a>

    <div class="row">

        <!-- IMAGES -->
        <div class="col-md-6 box">
            <?php if(!empty($images)): ?>
                <img id="mainImage" src="<?= $images[0] ?>" class="main-img mb-2">

                <div class="d-flex">
                    <?php foreach($images as $img): ?>
                        <img src="<?= $img ?>" class="thumb"
                             onclick="changeImage('<?= $img ?>')">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <img src="assets/no-image.png" class="main-img">
            <?php endif; ?>
        </div>

        <!-- INFOS -->
        <div class="col-md-6 box">

            <h2><?= htmlspecialchars($product['title']) ?></h2>

            <p><strong>Catégorie :</strong>
                <?= htmlspecialchars($product['category_name'] ?? 'Aucune') ?>
            </p>

            <h3 class="text-success">
                <?= number_format($product['price'],0,',',' ') ?> FCFA
            </h3>

            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <hr>

            <p>
                <strong>Vendeur :</strong>
                <?= htmlspecialchars($product['username']) ?>
            </p>

            <?php if(isset($_SESSION['user_id'])): ?>
                <p><strong>Téléphone :</strong>
                    <?= htmlspecialchars($product['phone']) ?>
                </p>

                <!-- <a href="contact_seller.php?user_id=<?= $product['user_id'] ?>&product_id=<?= $product['id'] ?>"
                   class="btn btn-primary">
                   📞 Contacter
                </a> -->
            <?php else: ?>
                <p><em>Connectez-vous pour voir le numéro</em></p>
                <a href="login.php" class="btn btn-warning">Se connecter</a>
            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>