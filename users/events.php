<?php
require "header_users.php";
require "../login.php";
require "../db_connect.php";

$info = [];

// Проверяем соединение с базой данных
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$sql_top_organizers = "SELECT u.user_id, u.username, AVG(r.rating) as average_rating 
                       FROM user u
                       JOIN ratings r ON u.user_id = r.organizer_id
                       GROUP BY u.user_id, u.username
                       ORDER BY average_rating DESC
                       LIMIT 3";
$result_top_organizers = $con->query($sql_top_organizers);

// Выполняем запрос к базе данных
$sql = "SELECT * FROM event";
$result = $con->query($sql);

// Проверяем успешность выполнения запроса
if ($result->num_rows > 0) {
    // Преобразуем результат запроса в ассоциативный массив и сохраняем его в переменной $info
    while($row = $result->fetch_assoc()) {
        $info[] = $row;
    }
} else {
    echo "Нет доступных событий";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <main class="main">
            <section class="choose">
                <div class="container">
                    <h2 class="section-title">
                        Выбери свое событие
                    </h2>
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="tabs__btn">
                            <button type="submit" name="category" value="all" class="tabs__btn-item tabs__btn-item--active">Все события</button>
                            <button type="submit" name="category" value="Футбол" class="tabs__btn-item">Футбол</button>
                            <button type="submit" name="category" value="Баскетбол" class="tabs__btn-item">Баскетбол</button>
                            <button type="submit" name="category" value="Волейбол" class="tabs__btn-item">Волейбол</button>
                        </div>
                    </form>
                    <section class="top-organizers">
                        <h2>Топ-3 организаторов</h2>
                        <div class="organizers-list">
                            <?php
                            if ($result_top_organizers->num_rows > 0) {
                                while ($organizer = $result_top_organizers->fetch_assoc()) {
                                    echo '<div class="organizer">';
                                    echo '<a href="organizer.php?user_id=' . $organizer['user_id'] . '" class="organizer-name">' . htmlspecialchars($organizer['username']) . '</a>';
                                    echo '<p class="organizer-rating">Средняя оценка: ' . round($organizer['average_rating'], 2) . '</p>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>Нет данных о лучших организаторах.</p>';
                            }
                            ?>
                        </div>
                    </section>
                    <div class="tabs__content">
                        <div class="tabs__content-item">
                            <?php

                            $category = isset($_GET['category']) ? $_GET['category'] : 'all';

                            // Выполнение запроса к базе данных в зависимости от выбранной категории
                            if ($category === 'all') {
                                $sql = "SELECT * FROM event";
                            } else {
                                $sql = "SELECT * FROM event WHERE sport  = '$category'";
                            }

                            $result = $con->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<a href="event_details.php?id=' . $row['id'] . '" class="card-link">';
                                    echo '<div class="card">';
                                    echo '<img src="' . $row['eventImage'] . '" alt="" class="card__img">';
                                    echo '<h4 class="card__title">' . $row['sport'] . '</h4>';
                                    echo '<p class="card__date">' . $row['eventDate'] . '</p>';
                                    echo '<p class="card__text">' . $row['eventLocation'] . '</p>';
                                    echo '</div>';
                                    echo '</a>';
                                }
                            } else {
                                echo "Нет доступных событий";
                            }

                            // Закрытие соединения с базой данных
                            $con->close();
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="footer">
            <div class="footer__container">
                <div class="footer__menu-list">
                    <div class="footer__menu-logo">
                        <a href="#" class="footer__menu-link">
                            <img src="../images/logo.svg" alt="" class="logo__img">
                        </a>
                    </div>
                    <div class="footer__menu-definition">
                        <p class="footer__menu-text">Это спортивный сервис, предназначенный для поиска спортивных событий.<br>
                            Сервис предоставляет информацию о доступных местах,<br> описания услуг и возможности записи на мероприятие.
                        </p>
                    </div>
                </div>
                <div class="footer__menu-list">
                    <div class="footer__menu-item">
                        <a href="#" class="footer__menu-link footer__menu-text">+9 (999) 999-99-99</a>
                    </div>
                    <div class="footer__menu-item">
                        <a href="#" class="footer__menu-link footer__menu-text">help@sportify.com</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
