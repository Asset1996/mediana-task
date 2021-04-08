<?php 
    require 'config/db.php';
    $id = $_GET['id']; 
    $image_query = $pdo->prepare('SELECT `img` FROM `news` WHERE `id` = :id');
    $image_query->execute(['id' => $id]);
    $image = $image_query->fetch(PDO::FETCH_OBJ);
    $query = $pdo->prepare('DELETE FROM `news` WHERE `id` = :id');
    $query->execute(['id' => $id]);
    unlink('images/'.$image->img);
    header('Location: /');
?>
