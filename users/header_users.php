<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header_users.css">
    <title>Document</title>
</head>

<body>
    <header class="header header__main">
        <div class="container__header">
            <div class="header__iner">
                <div class="div__logo">
                    <a href="index.php" class="logo">
                        <img src="../images/logo.svg" alt="" class="logo__img">
                    </a>
                </div>
                <nav class="menu">
                    <ul class="menu__list">
                        <li class="menu__list-item"><a href="index.php" class="menu__list-link menu__list-link--active">Главная</a></li>
                        <li class="menu__list-item"><a href="events.php" class="menu__list-link">Спортивные события</a></li>
                        <li class="menu__list-item"><a href="#" class="menu__list-link">Категории</a></li>
                    </ul>
                </nav>
                <div class="div__auth" id="authSection">
                    <?php if (isset($_SESSION['username'])) { ?>
                        <div class="dropdown">
                            <button class="btn__auth" id="dropdownMenuButton"><?php echo htmlspecialchars($_SESSION['username']); ?></button>
                            <div class="dropdown-content" id="dropdownContent">
                                <a href="home.php">Мой кабинет</a>
                                <a href="organizer_chat.php">Сообщения</a>
                                <a href="../logout.php">Выйти</a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <a href="../auth.html" class="auth">
                            <button class="btn__auth">Войти</button>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>
</body>

</html>
