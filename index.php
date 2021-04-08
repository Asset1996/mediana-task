<?php 
    require 'config/db.php'; //Подкючаем базу данных и стартуем сессию
    unset($_SESSION['error_message']);    //Предварительно очищаем переменную error_message из сессий
    if(isset($_POST['_login_submit'])){   //Если нажата кнопка по name=_login_submit
        $login = $_POST['login_input'];   //Получаем значение инпута login_input и присваеваем его к $login
        $password = $_POST['pass_input']; //Получаем значение инпута pass_input и присваеваем его к $password
        $errors = array();                //объявляем переменную $errors типа массив

        if(trim($login) == ''){                 //Если $login пусто
            $errors[] = 'Вы не ввели логин!';   //то добавляем в массив $errors данное сообщение
        }
        if(trim($password) == ''){
            $errors[] = 'Вы не ввели пароль!';
        }

        $query = $pdo->prepare('SELECT * FROM `users` WHERE `login` = :login'); //Создаем sql запрос - Получить все поля из таблицы users, где поле login равно значению переменной :login
        $query->execute([                                                       //Запускаем запрос на исполнение, :login передаем значение $login
            'login' => $login
        ]);
        $user = $query->fetch(PDO::FETCH_OBJ);   //Полчаем результат выполнения sql запроса в виде объекта
                
        if(empty($user)){                                             //Если полученный объект пуст 
            $errors[] = "Пользователя с именем $login не существует"; //то в массив $errors добавляем данное сообщение об ошибке
        }else{                                                        //Если полученный объект имеет значение
            if(password_verify($password, $user->password)){          //то прверяем на соответствие введенный пользователем пароль($password) с шифрованным паролем из БД ($user->password)
                $_SESSION['user_verified'] = $user;                   //Если TRUE, то создаем в сессий переменную user_verified и передаем ей всю информацию о пользователе из БД
            }else{                                                    //Если FALSE
                $errors[] = 'Неправильно введен пароль!';             //То $errors добавлем данное сообщение
                unset($_SESSION['user_verified']);                    //И удаляем из сессий user_verified
            };
        }

        if(isset($errors)){                                           //Если массив $errors не пустой
            $_SESSION['error_message'] = $errors[0];                  //то записываем в error_message в сессий значение нулевого элемента массива $errors
        }
    }
    if(isset($_POST['_logout_button'])){       //Если нажали кнопку по name=_logout_button
        unset($_SESSION['user_verified']);     //То удаляем из сессий user_verified
    }
?>

<?php 
    $query = $pdo->prepare('SELECT * FROM news');   //Создаем sql запрос - Получить все поля из таблицы news,
    $query->execute();                              //Исполянем запрос  
    $data = $query->fetchAll(PDO::FETCH_OBJ);       //Закидываем ВЕСЬ результат в виде объекта в переменную $data
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require('layout/head.php') ?> <!--Подключаем head -->
</head>
<body>
    <?php require('layout/header.php') ?> <!--Подключаем заголовок header -->
    <div class="main_wrapper">
        <?php if(isset($_SESSION['error_message'])): ?>   <!-- Если в сессий есть переменная error_message -->
            <div class="_errors">
                <?php echo $_SESSION['error_message']; ?> <!-- то выводим ее значение на экран -->
            </div>
        <?php endif; ?>
        <?php if(!isset($_SESSION['user_verified'])): ?>    <!-- Если в сессий НЕТ переменной user_verified (то значит пользователь НЕ авторизован) -->
            <div class="_auth_form">
                <form method="post">                        <!-- то выводим на экран форму авторизаций ползователя -->
                    <p><h4>Авторизация</h4></p>
                    <p><input id="_login_input" type="text" name="login_input" value="<?= @$login ?>"><label for="_login_input">Логин</label></p>
                    <p><input id="_pass_input" type="text" name="pass_input" value="<?= @$password ?>"><label for="_pass_input">Пароль</label></p>
                    <p><button type="submit" name="_login_submit">Войти</button></p>
                    <p>Еще не зарегистрированы?</p>
                    <p>Тогда <a class="_register_button" href="/register.php">Жмите сюда</a> для регистраций</p> 
                </form>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['user_verified'])): ?>  <!-- Если в сессий ЕСТЬ переменная user_verified (то значит пользователь авторизован) -->
        <div class="news_grid">
            <?php foreach($data as $data_item): ?>       <!-- то выводим на экран все записи с новостями из БД по цику foreach -->
                <div class="news_item">
                    <a href="/view.php?id=<?= $data_item->id ?>"><img src="/images/<?= $data_item->img ?>" alt="image"></a> 
                    <span><?= $data_item->title ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <?php require('layout/footer.php') ?> <!--Подключаем footer -->
</body>
