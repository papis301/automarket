<?php
require_once 'db.php';

// 🔍 filtre catégorie
$categoryId = $_GET['category'] ?? null;

// 📦 produits
$sql = "
SELECT p.*, 
(SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
FROM products p
";

if ($categoryId) {
    $sql .= " WHERE p.category_id = :cat";
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);

if ($categoryId) {
    $stmt->execute(['cat' => $categoryId]);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 📂 catégories
$categories = $pdo->query("
SELECT c.*, 
(SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as total
FROM categories c
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Shop - AutoMarket</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
<link rel="stylesheet" href="assets/css/vendor/ion-fonts.css">
<link rel="stylesheet" href="assets/css/plugins/slick.css">
<link rel="stylesheet" href="assets/css/plugins/nice-select.css">
<link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="template-color-1">

<div class="main-wrapper">

<!-- 🔝 HEADER (tu peux inclure ton header.php ici si besoin) -->

<!-- 🧭 BREADCRUMB -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Shop</h2>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="active">Boutique</li>
            </ul>
        </div>
    </div>
</div>

<!-- 🛒 SHOP -->
<div class="shop-content_wrapper">
<div class="container-fluid">
<div class="row">

<!-- 📂 SIDEBAR -->
<div class="col-lg-3 col-md-5">

<div class="uren-sidebar-catagories_area">

<div class="category-module uren-sidebar_categories">
<div class="category-module_heading">
<h5>Catégories</h5>
</div>

<div class="module-body">
<ul class="module-list_item">

<li>
<a href="shop.php">Toutes <span>(<?= count($products) ?>)</span></a>
</li>

<?php foreach ($categories as $c): ?>
<li>
<a href="shop.php?category=<?= $c['id'] ?>">
<?= htmlspecialchars($c['name']) ?>
<span>(<?= $c['total'] ?>)</span>
</a>
</li>
<?php endforeach; ?>

</ul>
</div>
</div>

</div>
</div>

<!-- 🛍️ PRODUITS -->
<div class="col-lg-9 col-md-7">

<div class="shop-toolbar">
<h5>
<?= $categoryId ? "Produits filtrés" : "Tous les produits" ?>
</h5>
</div>

<?php if (empty($products)): ?>
<div class="alert alert-warning">
Aucun produit trouvé
</div>
<?php endif; ?>

<div class="shop-product-wrap grid gridview-3 img-hover-effect_area row">

<?php foreach ($products as $p): ?>

<div class="col-lg-4 col-md-6 col-sm-6">
<div class="product-slide_item">
<div class="inner-slide">
<div class="single-product">

<div class="product-img">
<a href="product_details.php?id=<?= $p['id'] ?>">

<?php if (!empty($p['main_image'])): ?>
<img class="primary-img" src="<?= $p['main_image'] ?>">
<?php else: ?>
<img class="primary-img" src="assets/images/no-image.png">
<?php endif; ?>

</a>

<div class="sticker">
<span class="sticker">New</span>
</div>

</div>

<div class="product-content">
<div class="product-desc_info">

<h6>
<a class="product-name" href="product_details.php?id=<?= $p['id'] ?>">
<?= htmlspecialchars($p['name']) ?>
</a>
</h6>

<div class="price-box">
<span class="new-price">
<?= number_format($p['price'], 0, ',', ' ') ?> FCFA
</span>
</div>

</div>
</div>

</div>
</div>
</div>
</div>

<?php endforeach; ?>

</div>

</div>

</div>
</div>
</div>

</div>

<!-- 🔻 FOOTER -->
<!-- tu peux inclure footer.php ici -->

</div>

<!-- JS -->
<script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>
<script src="assets/js/plugins/slick.min.js"></script>
<script src="assets/js/plugins/nice-select.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>