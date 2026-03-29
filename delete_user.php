<?php
session_start();
require 'db.php';

// 🔒 super admin uniquement
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    exit("Accès refusé");
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=? AND role='admin'");
    $stmt->execute([$id]);
}

header("Location: superadmin_dashboard.php");