<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) exit("Produit introuvable");

// 🔒 récupérer produit
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) exit("Produit introuvable");

// 🔒 sécurité
if ($product['user_id'] != $_SESSION['user_id'] && 
   !in_array($_SESSION['role'], ['admin','super_admin'])) {
    exit("Accès refusé");
}

// catégories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// images
$stmtImg = $pdo->prepare("SELECT * FROM product_images WHERE product_id=?");
$stmtImg->execute([$id]);
$images = $stmtImg->fetchAll();


// ================= DELETE IMAGE =================
if (isset($_GET['delete_image'])) {

    $imgId = intval($_GET['delete_image']);

    $stmt = $pdo->prepare("SELECT * FROM product_images WHERE id=?");
    $stmt->execute([$imgId]);
    $img = $stmt->fetch();

    if ($img) {
        if (file_exists($img['image_path'])) {
            unlink($img['image_path']);
        }
        $pdo->prepare("DELETE FROM product_images WHERE id=?")->execute([$imgId]);
    }

    header("Location: edit_product.php?id=".$id);
    exit;
}


// ================= SET MAIN IMAGE =================
if (isset($_GET['set_main'])) {

    $imgId = intval($_GET['set_main']);

    $pdo->prepare("UPDATE product_images SET is_main=0 WHERE product_id=?")
        ->execute([$id]);

    $pdo->prepare("UPDATE product_images SET is_main=1 WHERE id=?")
        ->execute([$imgId]);

    header("Location: edit_product.php?id=".$id);
    exit;
}


// ================= UPDATE PRODUIT =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("
        UPDATE products 
        SET title=?, price=?, category_id=?, description=? 
        WHERE id=?
    ");
    $stmt->execute([$title, $price, $category_id, $description, $id]);

    // 📸 upload images
    if (!empty($_FILES['photos']['name'][0])) {

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        foreach ($_FILES['photos']['name'] as $key => $name) {

            $tmp = $_FILES['photos']['tmp_name'][$key];
            $newName = time().'_'.$name;
            $path = $uploadDir.$newName;

            if (move_uploaded_file($tmp, $path)) {

                // vérifier si image principale existe
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM product_images WHERE product_id=? AND is_main=1");
                $stmtCheck->execute([$id]);
                $isMain = $stmtCheck->fetchColumn() == 0 ? 1 : 0;

                $pdo->prepare("
                    INSERT INTO product_images (product_id, image_path, is_main)
                    VALUES (?, ?, ?)
                ")->execute([$id, $path, $isMain]);
            }
        }
    }

    header("Location: dashboard.php");
    exit;
}
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Modifier produit</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
img { object-fit: cover; }
</style>

</head>

<body class="container mt-4">

<h2>✏️ Modifier produit</h2>

<form method="POST" enctype="multipart/form-data">

<input class="form-control mb-2" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>

<input class="form-control mb-2" type="number" name="price" value="<?= $product['price'] ?>" required>

<select name="category_id" class="form-control mb-2">
<?php foreach($categories as $c): ?>
    <option value="<?= $c['id'] ?>" <?= $c['id']==$product['category_id']?'selected':'' ?>>
        <?= htmlspecialchars($c['name']) ?>
    </option>
<?php endforeach; ?>
</select>

<textarea name="description" class="form-control mb-2"><?= htmlspecialchars($product['description']) ?></textarea>


<!-- ================= IMAGES ================= -->
<h5>📸 Images du produit</h5>

<div class="d-flex flex-wrap gap-2 mb-3">

<?php foreach($images as $img): ?>
<div style="position:relative">

    <img src="<?= $img['image_path'] ?>" 
         style="width:120px;height:90px;border-radius:8px;">

    <?php if($img['is_main']): ?>
        <span style="position:absolute;top:5px;left:5px;background:green;color:#fff;padding:2px 6px;font-size:12px;">
            ⭐ principale
        </span>
    <?php endif; ?>

    <div style="position:absolute;bottom:5px;left:5px">

        <a href="?id=<?= $id ?>&set_main=<?= $img['id'] ?>" 
           class="btn btn-sm btn-warning">⭐</a>

        <a href="?id=<?= $id ?>&delete_image=<?= $img['id'] ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Supprimer ?')">🗑</a>

    </div>

</div>
<?php endforeach; ?>

</div>


<!-- ================= UPLOAD ================= -->
<input type="file" name="photos[]" multiple class="form-control" onchange="previewImages(event)">

<div id="preview" class="d-flex mt-2"></div>


<button class="btn btn-success mt-3">💾 Enregistrer</button>

</form>

<a href="dashboard.php" class="btn btn-secondary mt-3">Retour</a>


<!-- ================= PREVIEW JS ================= -->
<script>
function previewImages(event) {
    let preview = document.getElementById('preview');
    preview.innerHTML = '';

    Array.from(event.target.files).forEach(file => {
        let reader = new FileReader();

        reader.onload = function(e) {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100px';
            img.style.margin = '5px';
            img.style.borderRadius = '8px';
            preview.appendChild(img);
        }

        reader.readAsDataURL(file);
    });
}
</script>

</body>
</html>