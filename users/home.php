<?php
require "header_users.php";
date_default_timezone_set('Europe/Moscow'); // Установка часового пояса Москвы
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/home_users.css" />
    <title>Мой кабинет</title>
</head>
<body>
<div class="wrapper">
    <div class="profile-container">
        <img src="../images/card-1.jpg" alt="Profile Photo" class="profile-image">
        <div class="username"><?php echo strtoupper($_SESSION['username']); ?></div>
        <button onclick="window.location.href='createEvent.php'" class="create-event-btn">
            <img class="create-event-plus" src="../images/plus.svg">
            <span class="create-event-text">Создать событие</span>
        </button>
    </div>
    <main class="main">
        <div class="event-history">
            <div id="createdSortOptions" class="sort-options">
                <h2>Созданные:</h2>
                <select class="select-sort" onchange="sortEvents(this.value, 'created-events')">
                    <option value="byTime" selected>По времени</option>
                    <option value="bySport">По виду спорта</option>
                </select>
                
                <div class="created-events">
                    <?php
                    require_once "../db_connect.php"; // Подключаемся к базе данных

                    $user_id = $_SESSION["user_id"];

                    // Выбираем события, созданные пользователем
                    $sql_created_events = "SELECT * FROM `event` WHERE user_id = $user_id ORDER BY eventDate DESC";
                    $result_created_events = $con->query($sql_created_events);

                    if ($result_created_events->num_rows > 0) {
                        while ($created_event = $result_created_events->fetch_assoc()) {
                            echo '<div class="event ';
                            if (strtotime($created_event["eventDate"]) < time()) {
                                echo 'expired-event';
                            } else {
                                echo 'active-event';
                            }
                            echo '">';
                            if (strtotime($created_event["eventDate"]) < time()) {
                                echo '<img src="../images/time.svg">';
                            } else {
                                echo '<img src="../images/complete.svg">';
                            }
                            echo '<h3>' . $created_event["sport"] .','. '</h3>';
                            echo '<p>' . $created_event["eventDate"] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo "Вы еще не создали ни одного события.";
                    }
                    ?>
                </div>
            </div>
            <div id="joinedSortOptions" class="sort-options">
                <h2>История посещений:</h2>
                <select class="select-sort" onchange="sortEvents(this.value, 'joined-events')">
                    <option value="byTime" selected>По времени</option>
                    <option value="bySport">По виду спорта</option>
                </select>
                <div class="joined-events">
                    <?php
                    // Здесь нужно выполнить запрос для получения событий, на которые записался пользователь
                    $sql_joined_events = "SELECT e.* FROM `event` AS e JOIN `record` AS r ON e.id = r.event_id WHERE r.user_id = $user_id ORDER BY eventDate DESC";
                    $result_joined_events = $con->query($sql_joined_events);

                    if ($result_joined_events->num_rows > 0) {
                        while ($joined_event = $result_joined_events->fetch_assoc()) {
                            echo '<div class="event ';
                            if (strtotime($joined_event["eventDate"]) < time()) {
                                echo 'expired-event';
                            } else {
                                echo 'active-event';
                            }
                            echo '">';
                            if (strtotime($joined_event["eventDate"]) < time()) {
                                echo '<img src="../images/time.svg">';
                            } else {
                                echo '<img src="../images/complete.svg">';
                            }
                            echo '<h3>' . $joined_event["sport"] .','. '</h3>';
                            echo '<p>' . $joined_event["eventDate"] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo "Вы еще не записались ни на одно событие.";
                    }
                    ?>
                </div>
            </div>
        </div>
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
<script>
    function sortEvents(sortType, containerClass) {
        const eventsContainer = document.querySelector(`.${containerClass}`);
        const events = Array.from(eventsContainer.querySelectorAll('.event'));
        events.sort((a, b) => {
            const dateA = new Date(a.querySelector('p').textContent);
            const dateB = new Date(b.querySelector('p').textContent);
            if (sortType === 'byTime') {
                return dateA - dateB;
            } else if (sortType === 'bySport') {
                const sportA = a.querySelector('h3').textContent;
                const sportB = b.querySelector('h3').textContent;
                return sportA.localeCompare(sportB);
            }
        });
        events.forEach(event => {
            eventsContainer.appendChild(event);
        });
    }
</script>
</body>
</html>


