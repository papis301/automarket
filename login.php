<?php
session_start();
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // Nettoyage téléphone
    $phone = preg_replace('/[^\d]/', '', $phone);

    if ($phone === '' || $password === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // 🔥 SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            header("Location: index.php");
            exit;

        } else {
            $errors[] = "Téléphone ou mot de passe incorrect.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Connexion | AutoMarket</title>

<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
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

<!-- 🧭 BREADCRUMB -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Connexion</h2>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="active">Se connecter</li>
            </ul>
        </div>
    </div>
</div>

<!-- 🔐 LOGIN -->
<div class="uren-login-register_area">
<div class="container">
<div class="row justify-content-center">

<div class="col-lg-6">

<form method="post">
<div class="login-form">

<h4 class="login-title text-center">Se connecter</h4>

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
<label>Téléphone</label>
<input type="text" name="phone"
value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
placeholder="770000000" required>
</div>

<div class="col-12 position-relative mb--20">
<label>Mot de passe</label>
<input type="password" name="password" id="password" required>
<span onclick="togglePassword('password', this)">👁️</span>
</div>

<div class="col-12">
<button class="uren-login_btn w-100">Connexion</button>
</div>

</div>

<div class="text-center mt-3">
<a href="register.php">Pas de compte ? S'inscrire</a>
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