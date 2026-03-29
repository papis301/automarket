<?php
require 'db.php';

$username = "superadmin";
$phone = "766487420";
$password = password_hash("Passer@2026", PASSWORD_DEFAULT);
$role = "super_admin";

$stmt = $pdo->prepare("
INSERT INTO users (username, phone, password, role)
VALUES (?, ?, ?, ?)
");

$stmt->execute([$username, $phone, $password, $role]);

echo "Super admin créé ✅";