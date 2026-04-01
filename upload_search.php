<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['image'])) {
        die("Aucune image envoyée");
    }

    $file = $_FILES['image'];
    $type = $_POST['type'];

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir);

    $fileName = time() . "_" . basename($file['name']);
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $target)) {

        if ($type === 'sell') {
            // Redirige vers ajout produit
            header("Location: add_product.php?image=" . urlencode($target));
        } else {
            // Recherche (tu peux améliorer plus tard avec IA)
            header("Location: search.php?image=" . urlencode($target));
        }
        exit;
    } else {
        echo "Erreur upload";
    }
}