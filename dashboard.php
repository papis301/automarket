<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// PRODUITS
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title>Mon Compte | AutoMarket</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
<link rel="stylesheet" href="assets/css/style.css">

<style>
.product-card img {
    height:180px;
    object-fit:cover;
}

/* MOBILE */
@media (max-width: 768px) {

    .product-card img {
        height:150px;
    }

    .nav.flex-column {
        flex-direction: row !important;
        overflow-x: auto;
        white-space: nowrap;
        gap: 10px;
        padding-bottom: 10px;
    }

    .nav.flex-column .nav-item {
        display: inline-block;
    }

    .nav.flex-column .nav-link {
        font-size: 12px;
        padding: 6px 10px;
        background: #f1f1f1;
        border-radius: 6px;
    }

    .myaccount-dashboard p {
        font-size: 14px;
    }

    .btn-sm {
        font-size: 12px;
        padding: 5px 8px;
    }

}
</style>

</head>

<body class="template-color-1">

<div class="main-wrapper">

<!-- 🧭 BREADCRUMB -->
<div class="breadcrumb-area">
<div class="container">
<div class="breadcrumb-content">
<h2>Mon compte</h2>
<ul>
<li><a href="index.php">Accueil</a></li>
<li class="active">Dashboard</li>
</ul>
</div>
</div>
</div>

<!-- 🧑 DASHBOARD -->
<div class="account-page-area">
<div class="container-fluid">
<div class="row">

<!-- MENU GAUCHE -->
<div class="col-lg-3 col-12 mb-3">
<ul class="nav flex-column">

<li class="nav-item">
<a class="nav-link active" href="#">📊 Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="add_product.php">➕ Ajouter produit</a>
</li>

<li class="nav-item">
<a class="nav-link" href="products.php">📦 Tous les produits</a>
</li>

<?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin'): ?>
<li class="nav-item">
<a class="nav-link" href="categories.php">📂 Catégories</a>
</li>
<?php endif; ?>

<li class="nav-item">
<a class="nav-link text-danger" href="logout.php">🚪 Déconnexion</a>
</li>

</ul>
</div>

<!-- CONTENU -->
<div class="col-lg-9 col-12">

<div class="myaccount-dashboard mb-4">
<p>
Bonjour <b><?= htmlspecialchars($_SESSION['username']) ?></b> 👋
</p>
<p>Voici vos produits publiés :</p>
</div>

<div class="row">

<?php if(empty($products)): ?>
<div class="alert alert-info">
Aucun produit ajouté.
</div>
<?php endif; ?>

<?php foreach($products as $p): ?>

<div class="col-lg-4 col-md-6 col-6 mb-3">
<div class="card product-card">

<a href="product_details.php?id=<?= $p['id'] ?>">
<img src="<?= $p['main_image'] ?? 'assets/no-image.png' ?>">
</a>

<div class="card-body">

<h6><?= htmlspecialchars($p['title']) ?></h6>

<p class="text-success">
<?= number_format($p['price'],0,',',' ') ?> FCFA
</p>

<div class="d-flex flex-column flex-md-row gap-2">

<a href="edit_product.php?id=<?= $p['id'] ?>"
class="btn btn-primary btn-sm">
Modifier
</a>

<a href="delete_product.php?id=<?= $p['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Supprimer ?')">
Supprimer
</a>

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

<script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>

</body>
</html>