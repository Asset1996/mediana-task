<?php require 'config/db.php'; ?> <!-- DB connection. Переменная $pdo доступна в файле -->
<?php 
    $id = $_GET['id']; 
    $query = $pdo->prepare('SELECT * FROM `news` WHERE `id` = :id');
    $query->execute(['id' => $id]);
    $single_news = $query->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require('layout/head.php') ?> <!--Подключаем head --> 
</head>

<body>
<?php require('layout/header.php') ?> <!--Подключаем заголовок header -->

<div class="main_wrapper">
    <div class="news_view_wrapper">
        <div class="news_view">
            <div class="single_new_title">
                <h3><?= $single_news->title ?></h3>
                <span>Дата публикаций: <?= $single_news->dat_of_pub ?></span>
            </div>
            <div class="single_new_main">
                <img src= "/images/<?= $single_news->img ?>" alt="image 1">
                <span><?= $single_news->content ?></span>
            </div>
            <div class="single_new_actions">
                <a href="/del.php?id=<?= $single_news->id ?>" onclick="return confirm('Вы точно хотите удалить новость?')"><button>Удалить</button></a>
                <a href="/update.php?id=<?= $single_news->id ?>"><button>Редактировать</button></a>
            </div>
        </div>
    </div>
</div>
<?php require('layout/footer.php') ?> <!--Подключаем footer -->
</body>
