<?php 
    $dsn = 'mysql:host=localhost; dbname=task_news'; //Создаем Data source name - инфо о БД: 1-Имя хоста, 2-имя БД
    $pdo = new PDO($dsn, 'mysql', 'mysql');          //Создаем экземпляр объекта PDO
    session_start();                                 //Запускаем сессию
?>
