<?php
session_start();
require 'db.php';

if($_POST){
    $stmt = $pdo->prepare("INSERT INTO products(user_id,title,price,description) VALUES(?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['title'], $_POST['price'], $_POST['description']]);

    $product_id = $pdo->lastInsertId();

    foreach($_FILES['images']['tmp_name'] as $k=>$tmp){
        $name = uniqid().$_FILES['images']['name'][$k];
        move_uploaded_file($tmp,"uploads/".$name);

        $pdo->prepare("INSERT INTO product_images(product_id,image_path) VALUES(?,?)")
            ->execute([$product_id,"uploads/".$name]);
    }

    header("Location: dashboard.php");
}
?>

<form method="post" enctype="multipart/form-data">
<input name="title">
<input name="price">
<textarea name="description"></textarea>
<input type="file" name="images[]" multiple>
<button>Ajouter</button>
</form>