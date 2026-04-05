<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit("Produit introuvable.");

// PRODUIT
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

// IMAGES
$stmtImg = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$stmtImg->execute([$id]);
$images = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

// PRODUITS SIMILAIRES 🔥
$stmtSimilar = $pdo->prepare("
    SELECT p.*, 
    (SELECT image_path FROM product_images WHERE product_id=p.id LIMIT 1) as img
    FROM products p
    WHERE p.category_id = ? AND p.id != ?
    ORDER BY p.id DESC LIMIT 4
");
$stmtSimilar->execute([$product['category_id'], $id]);
$similar = $stmtSimilar->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($product['title']) ?></title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
body { background:#f5f5f5; }

.main-img {
    height:400px;
    object-fit:cover;
    width:100%;
    border-radius:10px;
}

.thumb {
    width:80px;
    height:60px;
    object-fit:cover;
    cursor:pointer;
    margin-right:5px;
    border-radius:6px;
}

.box {
    background:white;
    padding:20px;
    border-radius:10px;
}

.similar img {
    height:150px;
    object-fit:cover;
}
</style>

<script>
function changeImage(src){
    document.getElementById("mainImage").src = src;
}
</script>

</head>

<body>

<div class="container mt-5">

<a href="shop.php" class="btn btn-dark mb-3">⬅ Retour boutique</a>

<div class="row">

<!-- 🖼️ IMAGES -->
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

<!-- 📋 INFOS -->
<div class="col-md-6 box">

<h2><?= htmlspecialchars($product['title']) ?></h2>

<p class="text-muted">
Catégorie : <?= htmlspecialchars($product['category_name'] ?? 'Aucune') ?>
</p>

<h3 class="text-success">
<?= number_format($product['price'],0,',',' ') ?> FCFA
</h3>

<hr>

<p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

<hr>

<p><strong>Vendeur :</strong> <?= htmlspecialchars($product['username']) ?></p>

<?php if(isset($_SESSION['user_id'])): ?>

<p><strong>Téléphone :</strong> <?= htmlspecialchars($product['phone']) ?></p>

<a href="tel:<?= $product['phone'] ?>" class="btn btn-success mb-2">
📞 Appeler
</a>

<a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$product['phone']) ?>" 
   class="btn btn-success mb-2">
💬 WhatsApp
</a>

<?php else: ?>

<p><em>Connectez-vous pour voir le numéro</em></p>
<a href="login.php" class="btn btn-warning">Se connecter</a>

<?php endif; ?>

</div>

</div>

<!-- 🔥 PRODUITS SIMILAIRES -->
<div class="mt-5">

<h4>🔥 Produits similaires</h4>

<div class="row">

<?php foreach($similar as $s): ?>

<div class="col-md-3 similar mb-3">
<div class="box">

<a href="product.php?id=<?= $s['id'] ?>">

<?php if($s['img']): ?>
<img src="<?= $s['img'] ?>" class="w-100">
<?php else: ?>
<img src="assets/no-image.png" class="w-100">
<?php endif; ?>

<h6 class="mt-2"><?= htmlspecialchars($s['title']) ?></h6>

<strong><?= number_format($s['price'],0,',',' ') ?> FCFA</strong>

</a>

</div>
</div>

<?php endforeach; ?>

</div>

</div>

</div>

</body>
</html>