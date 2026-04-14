<?php
session_start();
require 'db.php';

// 🔒 sécurité
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    exit("Produit introuvable");
}

// vérifier propriétaire
$stmt = $pdo->prepare("SELECT user_id FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    exit("Produit non trouvé");
}

// 🔐 sécurité utilisateur
if ($product['user_id'] != $_SESSION['user_id'] 
    && $_SESSION['role'] !== 'admin' 
    && $_SESSION['role'] !== 'super_admin') {
    exit("Accès refusé");
}

// supprimer images
$stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
$stmt->execute([$id]);

// supprimer produit
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;