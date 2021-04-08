<?php 
    require 'config/db.php';
    $id = $_GET['id'];
    $query = $pdo->prepare('SELECT * FROM `news` WHERE `id`=:id');
    $query->execute(['id' => $id]);
    $item = $query->fetch(PDO::FETCH_OBJ);
    
    if(isset($_POST['update_button'])){
        $title = $_POST['news_title'];
        $content = $_POST['news_content'];

        $query_update = $pdo->prepare('UPDATE news SET title = :title, content = :content, img = :img WHERE id = :id');
        $query_update->execute([
            'id' => $id,
            'title' => $title ? $title : $item->title,
            'content'=> $content ? $content : $item->content,
            'img' => !empty($_FILES['news_img']['name']) ? $_FILES['news_img']['name'] : $item->img
        ]);
        if(!empty($_FILES['news_img']['name'])){
            $name = $_FILES['news_img']['name'];
            $tmp_name = $_FILES['news_img']['tmp_name'];
            move_uploaded_file($tmp_name, 'images/'.$name);
            if(file_exists('images/'.$item->img)){
                unlink('images/'.$item->img);
            }
        }
        header("Location: /view.php?id=$item->id");
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require('layout/head.php') ?> <!--Подключаем head -->   
</head>
<body>
<?php require('layout/header.php') ?> <!--Подключаем заголовок header -->
    <div class="main_wrapper">
        <div class="_news_add_form">
            <form method="post" enctype="multipart/form-data">
                <span><h4>Редактировать новость</h4></span>
                <span>Заголовок новости</span>
                <p><input id="_news_title" type="text" name="news_title" value="<?= $item->title ?>"></p>

                <span>Контент новости</span>
                <p><textarea id="_news_content" cols="30" rows="10" name="news_content"><?= @$item->content ?></textarea></p>

                <span>Обложка</span>
                <p><input id="_news_img" type="file" name="news_img" accept=".jpg, .png"></p>

                <p><button type="submit" name="update_button">Внести изменения</button></p>
            </form>    
        </div>
    </div>
    <?php require('layout/footer.php') ?> <!--Подключаем footer -->
</body>
