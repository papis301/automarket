<?php
session_start();
require_once 'db.php';

/**
 * 🔥 Récupérer tous les produits avec 1 image
 */
$stmt = $pdo->query("
    SELECT p.*, 
    (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image
    FROM products p
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Auto Market</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="assets/css/vendor/fontawesome-stars.css">
    <link rel="stylesheet" href="assets/css/vendor/ion-fonts.css">
    <link rel="stylesheet" href="assets/css/plugins/slick.css">
    <link rel="stylesheet" href="assets/css/plugins/animate.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/plugins/lightgallery.min.css">
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="main-wrapper">

<!-- ================= HEADER ================= -->
<header class="header-main_area header-main_area-2 header-main_area-3">
    <div class="header-middle_area">
        <div class="container-fluid">
            <div class="row">

                <!-- LOGO -->
                <div class="col-lg-3">
                    <div class="header-logo_area">
                        <a href="index.php">
                            <img src="assets/images/menu/logo/2.png">
                        </a>
                    </div>
                </div>

                <!-- SEARCH -->
                <div class="col-lg-6">
                    <form class="hm-searchbox">
                        <input type="text" placeholder="Rechercher un véhicule...">
                        <button class="header-search_btn"><i class="ion-ios-search-strong"></i></button>
                    </form>
                </div>

                <!-- USER -->
                <div class="col-lg-3 text-end">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php">Mon compte</a> |
                        <a href="logout.php">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php">Connexion</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</header>

<!-- ================= SLIDER ================= -->
<div class="uren-slider_area">
    <div class="main-slider">

        <div class="single-slide bg-5">
            <div class="slider-content">
                <h3>Auto Market</h3>
                <p>Achetez & vendez vos voitures</p>
                <a class="uren-btn" href="#">Voir les annonces</a>
            </div>
        </div>

        <div class="single-slide bg-6">
            <div class="slider-content">
                <h3>Meilleures offres</h3>
                <p>Trouvez votre véhicule idéal</p>
            </div>
        </div>

    </div>
</div>

<!-- ================= PRODUITS ================= -->
<div class="shop-content_wrapper mt-5">
    <div class="container">
        <div class="row">

            <?php foreach ($products as $p): ?>
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="single-product">

                        <div class="product-img">
                            <a href="product_details.php?id=<?= $p['id'] ?>">

                                <?php if($p['main_image']): ?>
                                    <img class="primary-img" src="<?= $p['main_image'] ?>">
                                <?php else: ?>
                                    <img src="assets/images/product/medium-size/1-1.jpg">
                                <?php endif; ?>

                            </a>
                        </div>

                        <div class="product-content">
                            <h6>
                                <a href="product_details.php?id=<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['title']) ?>
                                </a>
                            </h6>

                            <div class="price-box">
                                <span class="new-price">
                                    <?= number_format($p['price'],0,',',' ') ?> FCFA
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>

</div>

<!-- ================= JS ================= -->
<script src="assets/js/vendor/jquery.min.js"></script>
<script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="assets/js/plugins/slick.min.js"></script>
<script src="assets/js/plugins/jquery.nice-select.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
$('.main-slider').slick({
    autoplay:true,
    dots:true,
    arrows:true
});
</script>

</body>
</html>