<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($phone === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'super_admin') {
                    header("Location: superadmin_dashboard.php");
                } 
                elseif ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } 
                else {
                    header("Location: dashboard.php");
                }
            exit;

        } else {
            $error = "Numéro ou mot de passe incorrect.";
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion | Auto Market</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS UREN -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="template-color-1">

<div class="main-wrapper">

    <!-- HEADER -->
    <header class="header-main_area">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <h2>AUTO MARKET</h2>
            <a href="index.php" class="btn btn-dark">🏠 Accueil</a>
        </div>
    </header>

    <!-- LOGIN -->
    <div class="uren-login-register_area section-space--ptb_90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">

                    <div class="login-form">

                        <h3 class="heading mb-20">Connexion</h3>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="login.php">

                            <div class="default-form-box mb-20">
                                <label>Numéro de téléphone *</label>
                                <input type="tel" name="phone" required
                                       placeholder="770000000"
                                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            </div>

                            <div class="default-form-box mb-20">
                                <label>Mot de passe *</label>
                                <input type="password" name="password" required>
                            </div>

                            <div class="login_submit">
                                <button class="uren-btn-2" type="submit">
                                    Se connecter
                                </button>
                            </div>

                        </form>

                        <p class="mt-3">
                            Pas encore inscrit ?
                            <a href="register.php">Créer un compte</a>
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-area text-center bg-dark text-white py-3">
        <p>© <?= date('Y') ?> Auto Market</p>
    </footer>

</div>

<!-- JS -->
<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>

</body>
</html><?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($phone === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;

        } else {
            $error = "Numéro ou mot de passe incorrect.";
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion | Auto Market</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS UREN -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="template-color-1">

<div class="main-wrapper">

    <!-- HEADER -->
    <header class="header-main_area">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <h2>AUTO MARKET</h2>
            <a href="index.php" class="btn btn-dark">🏠 Accueil</a>
        </div>
    </header>

    <!-- LOGIN -->
    <div class="uren-login-register_area section-space--ptb_90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">

                    <div class="login-form">

                        <h3 class="heading mb-20">Connexion</h3>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="login.php">

                            <div class="default-form-box mb-20">
                                <label>Numéro de téléphone *</label>
                                <input type="tel" name="phone" required
                                       placeholder="770000000"
                                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            </div>

                            <div class="default-form-box mb-20">
                                <label>Mot de passe *</label>
                                <input type="password" name="password" required>
                            </div>

                            <div class="login_submit">
                                <button class="uren-btn-2" type="submit">
                                    Se connecter
                                </button>
                            </div>

                        </form>

                        <p class="mt-3">
                            Pas encore inscrit ?
                            <a href="register.php">Créer un compte</a>
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-area text-center bg-dark text-white py-3">
        <p>© <?= date('Y') ?> Auto Market</p>
    </footer>

</div>

<!-- JS -->
<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>

</body>
</html>