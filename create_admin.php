<?php
session_start();
require 'db.php';

// 🔒 seulement super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    exit("Accès refusé");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($username && $phone && $_POST['password']) {

        $stmt = $pdo->prepare("INSERT INTO users (username, phone, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$username, $phone, $password]);

        $message = "Admin créé avec succès ✅";
    }
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">

<h2>👑 Créer un Admin</h2>

<?php if($message): ?>
<div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">

<input class="form-control mb-2" name="username" placeholder="Nom" required>
<input class="form-control mb-2" name="phone" placeholder="Téléphone" required>
<input class="form-control mb-2" type="password" name="password" placeholder="Mot de passe" required>

<button class="btn btn-success">Créer Admin</button>

</form>

<a href="admin_dashboard.php" class="btn btn-dark mt-3">Retour</a>

</div>

</body>
</html>