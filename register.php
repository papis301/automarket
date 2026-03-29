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
        $errors[] = "Nom d'utilisateur invalide.";
    }

    $phone_normalized = preg_replace('/[^\d+]/', '', $phone);
    if ($phone_normalized === '' || strlen($phone_normalized) < 6) {
        $errors[] = "Numéro de téléphone invalide.";
    }

    if ($password === '' || strlen($password) < 6) {
        $errors[] = "Mot de passe trop court (6 caractères min).";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Si pas d'erreurs
    if (empty($errors)) {

        // Vérifier si le téléphone existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute([$phone_normalized]);

        if ($stmt->fetch()) {
            $errors[] = "Ce numéro est déjà utilisé.";
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (username, phone, password)
                VALUES (?, ?, ?)
            ");

            if ($stmt->execute([$username, $phone_normalized, $password_hash])) {
                $success = "Inscription réussie ✔";

                // Option auto login
                /*
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['phone'] = $phone_normalized;
                $_SESSION['role'] = 'user';
                header("Location: index.php");
                exit;
                */
            } else {
                $errors[] = "Erreur serveur.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription | Auto Market</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <style>
        body { background:#f4f4f4; }
        .box {
            max-width:450px;
            margin:50px auto;
            background:white;
            padding:25px;
            border-radius:8px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="box">

    <h3 class="text-center mb-4">Créer un compte</h3>

    <!-- erreurs -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- succès -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="mb-3">
            <input type="text" name="username" class="form-control"
                   placeholder="Nom d'utilisateur"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <input type="tel" name="phone" class="form-control"
                   placeholder="770000000"
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control"
                   placeholder="Mot de passe" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password_confirm" class="form-control"
                   placeholder="Confirmer mot de passe" required>
        </div>

        <button class="btn btn-dark w-100">S'inscrire</button>

    </form>

    <div class="text-center mt-3">
        <a href="login.php">Déjà un compte ? Se connecter</a>
    </div>

    <div class="text-center mt-2">
        <a href="index.php" class="btn btn-secondary mt-2">⬅ Retour accueil</a>
    </div>

</div>

</body>
</html>