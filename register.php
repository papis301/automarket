<?php
session_start();
require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if ($username === '' || strlen($username) < 2) {
        $errors[] = "Nom invalide.";
    }

    $phone = preg_replace('/[^\d]/', '', $phone);
    if (strlen($phone) < 6) {
        $errors[] = "Téléphone invalide.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Mot de passe trop court (6 caractères min).";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Insertion
    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE phone=?");
        $stmt->execute([$phone]);

        if ($stmt->fetch()) {
            $errors[] = "Téléphone déjà utilisé.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (username, phone, password, role)
                VALUES (?, ?, ?, ?)
            ");

            if ($stmt->execute([$username, $phone, $hash, 'user'])) {

                // 🔥 AUTO LOGIN
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';

                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Erreur serveur.";
            }
        }
    }
}
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
<meta charset="utf-8">
<title>Inscription | AutoMarket</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
<link rel="stylesheet" href="assets/css/style.css">

<style>
.position-relative span {
    position:absolute;
    right:10px;
    top:38px;
    cursor:pointer;
}
</style>

</head>

<body class="template-color-1">

<div class="main-wrapper">

<!-- 🔝 HEADER (optionnel include) -->

<!-- 🧭 BREADCRUMB -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Inscription</h2>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="active">Créer un compte</li>
            </ul>
        </div>
    </div>
</div>

<!-- 🔐 REGISTER -->
<div class="uren-login-register_area">
<div class="container">
<div class="row justify-content-center">

<div class="col-lg-6">

<form method="post">
<div class="login-form">

<h4 class="login-title text-center">Créer un compte</h4>

<!-- erreurs -->
<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
<?php foreach ($errors as $e): ?>
<div><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="row">

<div class="col-12 mb--20">
<label>Nom d'utilisateur</label>
<input type="text" name="username"
value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
required>
</div>

<div class="col-12 mb--20">
<label>Téléphone</label>
<input type="text" name="phone"
placeholder="770000000"
value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
required>
</div>

<div class="col-md-6 position-relative mb--20">
<label>Mot de passe</label>
<input type="password" name="password" id="password" required>
<span onclick="togglePassword('password', this)">👁️</span>
</div>

<div class="col-md-6 position-relative mb--20">
<label>Confirmer</label>
<input type="password" name="password_confirm" id="password_confirm" required>
<span onclick="togglePassword('password_confirm', this)">👁️</span>
</div>

<div class="col-12">
<button class="uren-register_btn w-100">S'inscrire</button>
</div>

</div>

<div class="text-center mt-3">
<a href="login.php">Déjà un compte ? Se connecter</a>
</div>

</div>
</form>

</div>

</div>
</div>
</div>

</div>

<!-- JS -->
<script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>

<script>
function togglePassword(id, el){
    let input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
        el.textContent = "🙈";
    } else {
        input.type = "password";
        el.textContent = "👁️";
    }
}
</script>

</body>
</html>