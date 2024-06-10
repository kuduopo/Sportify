<?php
require_once "../db_connect.php";
require "header_users.php";
date_default_timezone_set('Europe/Moscow'); // Установка часового пояса Москвы
// Проверяем, есть ли параметр user_id в URL
if (isset($_GET['user_id'])) {
    $organizer_id = $_GET['user_id'];

    // Получаем информацию об организаторе
    $sql = "SELECT * FROM `user` WHERE user_id = $organizer_id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $organizer = $result->fetch_assoc();

        // Получаем оценки организатора
        $sql_rating = "SELECT AVG(rating) as average_rating FROM `ratings` WHERE organizer_id = $organizer_id";
        $result_rating = $con->query($sql_rating);
        $rating = $result_rating->fetch_assoc();
        $average_rating = round($rating['average_rating'], 2);

        // Получаем комментарии организатора
        $sql_comments = "SELECT c.comment, u.username FROM `comments` as c JOIN `user` as u ON c.user_id = u.user_id WHERE c.organizer_id = $organizer_id";
        $result_comments = $con->query($sql_comments);

        // Получаем историю созданных событий
        $sql_events = "SELECT * FROM `event` WHERE user_id = $organizer_id";
        $result_events = $con->query($sql_events);
    } else {
        echo "Организатор не найден";
        exit();
    }
} else {
    echo "Идентификатор организатора не указан";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/organizer.css">
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .rating-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .rating-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .rating-form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .rating-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .rating-form button:hover {
            background-color: #45a049;
        }

        .rating-form button[type="button"] {
            background-color: #f44336;
        }

        .rating-form button[type="button"]:hover {
            background-color: #da190b;
        }

        .rating-form h3 {
            margin-bottom: 20px;
        }

        .star-rating {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 30px;
            cursor: pointer;
            color: #ffcc00;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating input:checked ~ label {
            color: #ffcc00;
        }
    </style>
    <title>Информация об организаторе</title>
</head>
<body>
    <div class="wrapper">
        <main class="main">
            <section class="organizer-details">
                <div class="container">
                    <div class="organizer-details__info">
                        <p class="organizer-details__title">Имя организатора:</p>
                        <p class="organizer-details__name"><?php echo htmlspecialchars($organizer['username']); ?></p>
                        <p class="organizer-details__title">Средняя оценка:</p>
                        <div class="star-rating">
                            <?php
                            // Определяем количество звезд, соответствующее средней оценке
                            $numStars = floor($average_rating);
                            // Рисуем звезды
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $numStars) {
                                    echo '<label for="star' . $i . '">&#9733;</label>';
                                } else {
                                    echo '<label for="star' . $i . '">&#9734;</label>';
                                }
                            }
                            ?>
                        </div>
                        <div class="contact-organizer">
                            <a href="organizer_chat.php?organizer_id=<?php echo $organizer_id; ?>" class="contact-button">Связаться с организатором</a>
                        </div>
                    </div>
                    <div class="event-history">
                        <h3 class="title_h3">История созданных событий:</h3>
                        <?php
                        if ($result_events->num_rows > 0) {
                            while ($event = $result_events->fetch_assoc()) {
                                echo '<div class="event ';
                                if (strtotime($event["eventDate"]) < time()) {
                                    echo 'expired-event';
                                } else {
                                    echo 'active-event';
                                }
                                echo '">';
                                if (strtotime($event["eventDate"]) < time()) {
                                    echo '<img src="../images/time.svg">';
                                } else {
                                    echo '<img src="../images/complete.svg">';
                                }
                                echo '<h3>' . $event["sport"] .','. '</h3>';
                                echo '<p>' . $event["eventDate"] .','.'</p>';
                                echo '<p>' . $event["eventLocation"] . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo "Вы еще не создали ни одного события.";
                        }
                        ?>
                    </div>
                    <div class="organizer-comments">
                        <h3>Комментарии:</h3>
                        <ul class="comments-list">
                            <?php while ($comment = $result_comments->fetch_assoc()) { ?>
                                <li>
                                    <p><?php echo htmlspecialchars($comment['username']); ?>: <?php echo htmlspecialchars($comment['comment']); ?></p>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <form action="add_comment.php" method="POST">
                                <input type="hidden" name="organizer_id" value="<?php echo $organizer_id; ?>">
                                <textarea name="comment" placeholder="Ваш комментарий" required></textarea>
                                <button type="submit">Оставить комментарий</button>
                                <button id="show-rating-form">Оставить оценку</button>
                            </form>
                            
                            <div class="overlay" id="overlay">
                                <form action="add_rating.php" method="POST" class="rating-form">
                                    <input type="hidden" name="organizer_id" value="<?php echo $organizer_id; ?>">
                                    <label for="rating">Выберите оценку (от 1 до 5):</label><br>
                                    <div class="star-rating">
                                        <?php
                                        // Рисуем звезды для выбора оценки
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo '<input type="radio" id="star' . $i . '" name="rating" value="' . $i . '" required>';
                                            echo '<label for="star' . $i . '">&#9733;</label>';
                                        }
                                        ?>
                                    </div>
                                    <br><br>
                                    <button type="submit">Оставить оценку</button>
                                    <button type="button" id="close-rating-form">Отмена</button>
                                </form>
                            </div>
                            <script>
                                document.getElementById('show-rating-form').addEventListener('click', function() {
                                    document.getElementById('overlay').style.display = 'flex';
                                });

                                document.getElementById('close-rating-form').addEventListener('click', function() {
                                    document.getElementById('overlay').style.display = 'none';
                                });
                            </script>
                        <?php } else { ?>
                            <p>Чтобы оставить комментарий или оценку, <a href="../auth.html">войдите</a> в систему</p>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

