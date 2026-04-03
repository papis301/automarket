<?php
session_start();
require_once 'db.php';

// 🔒 accès sécurisé
if (!isset($_SESSION['user_id']) || 
   ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    exit("Accès refusé");
}

$error = "";
$success = "";

$uploadDir = "uploads/categories/";

// créer dossier si inexistant
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ➕ AJOUT
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if ($name !== '') {

        $imageName = $_FILES['image']['name'] ?? null;
        $newName = null;

        if (!empty($imageName)) {

            $tmp = $_FILES['image']['tmp_name'];
            $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            $allowed = ['jpg','jpeg','png','webp'];

            if (!in_array($ext, $allowed)) {
                $error = "Format image non autorisé";
            } else {
                $newName = time() . "_" . uniqid() . "." . $ext;
                move_uploaded_file($tmp, $uploadDir . $newName);
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
            $stmt->execute([$name, $newName]);
            $success = "✅ Catégorie ajoutée";
        }
    }
}

// ✏️ MODIFIER
if (isset($_POST['update'])) {

    $id = intval($_POST['id']);
    $name = trim($_POST['name']);

    // récupérer ancienne image
    $stmt = $pdo->prepare("SELECT image FROM categories WHERE id=?");
    $stmt->execute([$id]);
    $old = $stmt->fetch();

    $newName = $old['image'];

    if (!empty($_FILES['image']['name'])) {

        $imageName = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {

            $newName = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($tmp, $uploadDir . $newName);

            // supprimer ancienne image
            if (!empty($old['image']) && file_exists($uploadDir . $old['image'])) {
                unlink($uploadDir . $old['image']);
            }

        } else {
            $error = "Format image invalide";
        }
    }

    if ($name !== '' && !$error) {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, image=? WHERE id=?");
        $stmt->execute([$name, $newName, $id]);
        $success = "✅ Catégorie modifiée";
    }
}

// 🗑️ SUPPRIMER
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // vérifier usage
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id=?");
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        $error = "❌ Catégorie utilisée par des produits";
    } else {

        // récupérer image
        $stmt = $pdo->prepare("SELECT image FROM categories WHERE id=?");
        $stmt->execute([$id]);
        $cat = $stmt->fetch();

        if ($cat && !empty($cat['image'])) {
            $file = $uploadDir . $cat['image'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
        $stmt->execute([$id]);

        $success = "✅ Catégorie supprimée";
    }
}

// 📥 LISTE
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Catégories</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<style>
.img-cat {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
</style>

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="mb-4">📂 Gestion des catégories</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<!-- ➕ AJOUT -->
<form method="POST" enctype="multipart/form-data" class="mb-4 d-flex gap-2">
    <input type="text" name="name" class="form-control" placeholder="Nom catégorie" required>
    <input type="file" name="image" class="form-control">
    <button name="add" class="btn btn-success">Ajouter</button>
</form>

<!-- 📋 LISTE -->
<table class="table table-bordered bg-white">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Nom</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php foreach ($categories as $cat): ?>
<tr>

<td><?= $cat['id'] ?></td>

<td>
<?php if (!empty($cat['image'])): ?>
    <img src="<?= $uploadDir . $cat['image'] ?>" class="img-cat">
<?php else: ?>
    -
<?php endif; ?>
</td>

<td>
<form method="POST" enctype="multipart/form-data" class="d-flex gap-2">
<input type="hidden" name="id" value="<?= $cat['id'] ?>">

<input type="text" name="name" 
value="<?= htmlspecialchars($cat['name']) ?>" 
class="form-control">

<input type="file" name="image" class="form-control">
</td>

<td class="d-flex gap-2">

<button name="update" class="btn btn-primary btn-sm">Modifier</button>
</form>

<a href="?delete=<?= $cat['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Supprimer ?')">
Supprimer
</a>

</td>

</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php
$dashboardLink = "dashboard.php";

if ($_SESSION['role'] === 'super_admin') {
    $dashboardLink = "superadmin_dashboard.php";
} elseif ($_SESSION['role'] === 'admin') {
    $dashboardLink = "admin_dashboard.php";
}
?>

<a href="<?= $dashboardLink ?>" class="btn btn-secondary mt-3">
⬅ Retour Dashboard
</a>

</div>

</body>
</html>