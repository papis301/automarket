<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) exit("Connexion requise");

$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();
?>

<h2>Mes produits</h2>

<a href="add_product.php">Ajouter</a>

<?php foreach($products as $p): ?>
    <p><?= $p['title'] ?> - <?= $p['price'] ?></p>
<?php endforeach; ?>