<?php
require_once "../db_connect.php"; // Подключаемся к базе данных
require "header_users.php";

// Проверяем, есть ли параметр id в URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Выполняем запрос к базе данных для получения информации о событии
    $sql = "SELECT e.*, u.username FROM `event` AS e JOIN `user` AS u ON e.user_id = u.user_id WHERE e.id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Если событие найдено, выводим его детальную информацию
        $event = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/event_details.css">
            <title><?php echo htmlspecialchars($event['sport']); ?> - <?php echo htmlspecialchars($event['eventDate']); ?></title>
        </head>
        <body>
            <div class="wrapper">
                <main class="main">
                    <section class="event-details">
                        <div class="container">
                            <h2 class="section-title"><?php echo htmlspecialchars($event['sport']); ?></h2>
                            <div class="event-details__info">
                                <div class="event-details__image">
                                    <img src="<?php echo htmlspecialchars($event['eventImage']); ?>" alt="<?php echo htmlspecialchars($event['sport']); ?>" class="event-details__img">
                                </div>
                                <div class="event-details__btn">
                                    <?php if (isset($_SESSION["user_id"])) {
                                        if ($_SESSION["user_id"] != $event['user_id']) { ?>
                                            <button class="event-record__btn" id="recordButton" data-event-id="<?php echo $event_id; ?>">Записаться</button>
                                        <?php } else { ?>
                                            <button class="event-record__btn" disabled>Вы организатор этого события</button>
                                        <?php } 
                                    } else { ?>
                                        <a href="../auth.html"><button class="event-record__btn" id="loginButton">Чтобы записаться, авторизуйтесь</button></a>
                                        
                                    <?php } ?>
                                </div>

                                <div class="event-details__date-location">
                                    <p class="event-details__title">Дата:</p>
                                    <p class="event-details-info__date-location"><?php echo htmlspecialchars($event['eventDate']); ?></p>
                                    <p class="event-details__title">Место: </p>
                                    <p class="event-details-info__date-location"><?php echo htmlspecialchars($event['eventLocation']); ?></p>
                                </div>
                                <div class="event-details__description">
                                    <div class="event-details__description-info">
                                        <p class="event-details__title description__title">Доступные места:</p>
                                        <p class="event-details__spots" id="eventSpots"><?php echo htmlspecialchars($event['eventSpots']); ?></p>
                                    </div>
                                    <div class="event-details__description-info">
                                        <p class="event-details__title description__title">Организатор:</p>
                                        <p class="event-details__user">
                                        <a href="organizer.php?user_id=<?php echo $event['user_id']; ?>"><?php echo $event['username']; ?></a></p>
                                    </div>
                                </div>
                                <div class="event-details__comment-info">
                                    <p class="event-details__title description__title">Описание: </p>
                                    <p class="event-details__comment"><?php echo htmlspecialchars($event['eventDescription']); ?></p>
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

            <script>
                document.getElementById("recordButton").addEventListener("click", function() {
                    var eventId = this.getAttribute("data-event-id");
                    var userId = <?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 'null'; ?>;
                    var spots = parseInt(document.getElementById("eventSpots").innerHTML);
                    if (userId === null) {
                        alert("Вам нужно войти в систему, чтобы записаться на событие.");
                        window.location.href = 'auth.html'; // Перенаправляем на страницу входа
                        return;
                    }
                    if (spots > 0) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "record_event.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.status === 'success') {
                                    spots--;
                                    document.getElementById("eventSpots").innerHTML = spots;
                                    if (spots == 0) {
                                        document.getElementById("recordButton").disabled = true;
                                        document.getElementById("recordButton").innerHTML = "Все места заняты";
                                    }
                                }
                                alert(response.message);
                            }
                        };
                        xhr.send("id=" + eventId + "&user_id=" + userId);
                    } else {
                        alert("Все места уже заняты!");
                    }
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "Событие не найдено";
    }
} else {
    echo "Идентификатор события не указан";
}
?>
