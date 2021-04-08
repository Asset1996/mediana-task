<?php 
    require 'config/db.php'; //DB connection и старт сессий. Переменная $pdo доступна в файле
    unset($_SESSION['error_message']);
    if(isset($_POST['_news_add_button'])){

        $title = $_POST['title_news'];
        $content = $_POST['content_news'];
        $errors = array();
        if(trim($title) == ''){
            $errors[] = 'Введите заголовок новости';
        };
        if($content == ''){
            $errors[] = 'Напишите контент новости';
        };

        if(empty($_FILES['img_news']['tmp_name'])){
            $errors[] = 'Загрузите обложку';
        }else{
            if(empty($errors)){

                $image_name = $_FILES['img_news']['name'];
                $image_tmp = $_FILES['img_news']['tmp_name'];
                move_uploaded_file($image_tmp, 'images/'.$image_name);

                $query = $pdo->prepare('INSERT INTO news(title, content, img) VALUES(:title, :content, :img)');
                $query->execute([
                    'title' => $title,
                    'content' => $content,
                    'img' => $image_name 
                ]);
                header('Location: /');
            }
        }

        if(isset($errors)){
            $_SESSION['error_message'] = $errors[0];
        }
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require('layout/head.php') ?> <!--Подключаем head -->    
</head>
<body>
    <?php require('layout/header.php') ?> <!--Подключаем заголовок header -->

    <?php if(isset($_SESSION['user_verified'])): ?>
        
        <div class="main_wrapper">
            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="_errors">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
            <?php endif; ?>
            <div class="_news_add_form">
                <form action="/add.php" method="post" enctype="multipart/form-data">
                    <span><h4>Добавить новость</h4></span>
                    <span>Заголовок новости</span>
                    <p><input id="_title_news" type="text" name="title_news"></p>

                    <span>Контент новости</span>
                    <p><textarea id="_content_news" cols="30" rows="10" name="content_news"></textarea></p>
                    
                    <span>Обложка</span>
                    <p><input id="_img_news" type="file" name="img_news"></p>

                    <p><button type="submit" name="_news_add_button">Добавить новость</button></p>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php require('layout/footer.php') ?> <!--Подключаем footer -->
</body>
