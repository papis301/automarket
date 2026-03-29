<?php
session_start();
require_once 'db.php';

// Vérifier connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer catégories
$stmtCat = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmtCat->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);

    if ($title === '' || $price <= 0 || $category_id <= 0) {
        $error = "Veuillez remplir tous les champs.";
    } else {

        // Insert produit
        $stmt = $pdo->prepare("
            INSERT INTO products (user_id, title, price, description, category_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $title,
            $price,
            $description,
            $category_id
        ]);

        $product_id = $pdo->lastInsertId();

        // Upload images
        if (!empty($_FILES['images']['name'][0])) {

            $uploadDir = "uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {

                if ($_FILES['images']['error'][$key] === 0) {

                    $filename = basename($_FILES['images']['name'][$key]);
                    $newName = uniqid() . "_" . $filename;
                    $path = $uploadDir . $newName;

                    if (move_uploaded_file($tmp, $path)) {

                        $stmtImg = $pdo->prepare("
                            INSERT INTO product_images (product_id, image_path)
                            VALUES (?, ?)
                        ");
                        $stmtImg->execute([$product_id, $path]);
                    }
                }
            }
        }

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Ajouter un produit</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <style>
        body { background:#f5f5f5; }
        .box {
            max-width:600px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:8px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="box">

    <h3 class="mb-4 text-center">Ajouter un produit</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <input type="text" name="title" class="form-control"
                   placeholder="Nom du produit" required>
        </div>

        <div class="mb-3">
            <input type="number" name="price" class="form-control"
                   placeholder="Prix" required>
        </div>

        <div class="mb-3">
            <select name="category_id" class="form-control" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <textarea name="description" class="form-control"
                      placeholder="Description"></textarea>
        </div>

        <div class="mb-3">
            <label>Photos (plusieurs possibles)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
        </div>

        <button class="btn btn-success w-100">Ajouter le produit</button>

    </form>

    <a href="dashboard.php" class="btn btn-secondary w-100 mt-3">
        ⬅ Retour dashboard
    </a>

</div>

</body>
</html>