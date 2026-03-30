<?php
session_start();
require_once 'db.php';



// 🔒 accès uniquement admin + super_admin
if (!isset($_SESSION['user_id']) || 
   ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    exit("Accès refusé");
}

// ➕ AJOUT
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }
}

// ✏️ MODIFIER
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);
    }
}

// 🗑️ SUPPRIMER
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // 🔍 Vérifier si la catégorie est utilisée
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        $error = "❌ Impossible de supprimer : catégorie utilisée par des produits.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
        $stmt->execute([$id]);

        $success = "✅ Catégorie supprimée avec succès.";
    }
}

// 📥 LISTE
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>CRUD Catégories</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="container mt-5">

    <h2 class="mb-4">📂 Gestion des catégories</h2>

    <!-- ➕ Ajouter -->
    <form method="POST" class="mb-4 d-flex gap-2">
        <input type="text" name="name" class="form-control" placeholder="Nom catégorie" required>
        <button name="add" class="btn btn-success">Ajouter</button>
    </form>

    <!-- 📋 Liste -->
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>

                <td>
                    <form method="POST" class="d-flex">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" class="form-control">
                </td>

                <td class="d-flex gap-2">
                        <button name="update" class="btn btn-primary btn-sm">Modifier</button>
                    </form>

                    <a href="categories.php?delete=<?= $cat['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer ?')">
                       Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">
        ⬅ Retour Admin
    </a>

</div>

</body>
</html>