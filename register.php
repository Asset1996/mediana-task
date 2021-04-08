<?php
        require 'config/db.php'; //Подкючаем БД

        if(isset($_POST['_reg_submit'])){           //Если нажата кнопка по name=_reg_submit
            $login = $_POST['reg_login_input'];     //Получаем значение инпута reg_login_input и присваеваем его к $login
            $email = $_POST['reg_email_input'];     //Получаем значение инпута reg_email_input и присваеваем его к $email
            $password = $_POST['reg_pass_input'];   //Получаем значение инпута reg_pass_input и присваеваем его к $password
            $password2 = $_POST['reg_pass_input_2'];//Получаем значение инпута reg_pass_input_2 и присваеваем его к $password2
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);  //Шифруем введенный пользователем пароль - получаем на выоде случайно сгенерированное число. PASSWORD_DEFAULT - испльзуем алгоритм bcrypt.

            $errors = array();          //объявляем переменную $errors типа массив

            if(trim($login) == ''){                 //Если $login пусто
                $errors[] = 'Вы не ввели логин!';   //то добавляем в массив $errors данное сообщение
            }
            if(trim($email) == ''){
                $errors[] = 'Вы не ввели email!';
            }
            if($password == ''){
                $errors[] = 'Вы не ввели пароль!';
            }
            if($password2 == ''){
                $errors[] = 'Введите пароль повторно!';
            }
            if($password != $password2){
                $errors[] = 'Пароли не совпадают!';
            }

            if(!empty($errors)){                          //Если массив $errors не пустой
                $_SESSION['error_messages'] = $errors[0]; //то записываем в error_message в сессий значение нулевого элемента массива $errors
            }else{                                        //Если массив $errors пустой
                unset($_SESSION['error_messages']);       //то очищаем $errors
                $query = $pdo->prepare('INSERT INTO users(login, email, password) VALUES(:login, :email, :password)'); //Создаем sql запрос - добавить в поля login, email, password таблицы users значения :login, :email, :password
                $query->execute([               //исполняем sql запрос
                    'login' => $login,          //Передаем нужнгые значения из переменных $login, $email, $pass_hash
                    'email' => $email,
                    'password' => $pass_hash,
                ]);
                header('Location: /index.php'); //Перенапряавляем на главную страницу
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
    
    <div class="main_wrapper">
    
        <?php echo $_SESSION['error_messages'] ?> <!--В случае ошибок валидаций выводим сообщения об ошибках --> 

        <div class="_auth_form">
            
            <form method="post">  <!--Выводим форму регистраций пользователя --> 
                <p><h4>Регистрация</h4></p>
                <p><input id="_reg_input" type="text" name="reg_login_input" value="<?= @$login ?>"><label for="_reg_input">Логин</label></p>
                
                <p><input id="_reg_email_input" type="email" name="reg_email_input" value="<?= @$email ?>"><label for="_reg_email_input">Почта</label></p>

                <p><input id="_reg_pass_input" type="password" name="reg_pass_input" value="<?= @$password ?>"><label for="_reg_pass_input">Пароль</label></p>

                <p><input id="_reg_pass_input_2" type="password" name="reg_pass_input_2" value="<?= @$password2 ?>"><label for="_reg_pass_input_2">Введите пароль повторно</label></p>

                <p><button type="submit" name="_reg_submit">Зарегистрироваться</button></p>
                <p>Есть аккаунт?</p>
                <p>Тогда <a class="_register_button" href="/">Жмите сюда</a> для входа</p> 
            </form>
        </div>
        
    </div>
    <?php require('layout/footer.php') ?> <!--Подключаем footer -->
</body>
