<header>
    <nav class="menu_nav">
        <ul>
            <li><a href="/">Главная</a></li>
            <li><a href="">Контакты</a></li>
            <li><a href="/add.php">Добавить новость</a></li>
        </ul>
    </nav>
    <nav class="_logout_nav">
        <?php if(isset($_SESSION['user_verified'])): ?>
        <div class="_logout">
            <form method="post">
                <button type="submit" name="_logout_button">(<?= $_SESSION['user_verified']->login ?>)Выйти</button>
            </form>
        </div>
        <?php endif; ?>
    </nav>
    <nav class="_menu_button">
        <div class="menu_bar_button">
            <div class="fa fa_up"></div>
            <div class="fa fa_mid"></div>
            <div class="fa fa_down"></div>
        </div>
    </nav>
</header>