<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("
SELECT p.*, 
(SELECT image_path FROM product_images WHERE product_id=p.id LIMIT 1) AS main_image
FROM products p ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Auto Market</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- HEADER -->
<div class="container-fluid bg-dark text-white p-3 d-flex justify-content-between">
    <h3>AUTO MARKET</h3>

    <div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn btn-light">Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-success">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- PRODUITS -->
<div class="container mt-4">
    <div class="row">

        <?php foreach($products as $p): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="card shadow-sm">

                <a href="product_details.php?id=<?= $p['id'] ?>">
                    <img src="<?= $p['main_image'] ?? 'assets/no-image.png' ?>"
                         style="height:220px;width:100%;object-fit:cover;">
                </a>

                <div class="card-body text-center">
                    <h6><?= htmlspecialchars($p['title']) ?></h6>
                    <b class="text-success">
                        <?= number_format($p['price'],0,',',' ') ?> FCFA
                    </b>
                </div>

            </div>

        </div>
        <?php endforeach; ?>

    </div>
</div>

</body>
</html>