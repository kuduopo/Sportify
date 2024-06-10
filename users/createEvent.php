<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="createEvent.css">
    <title>Sport Events</title>
</head>
<body>
    <div class="container">
        <h2>Создай свое событие</h2>
        <form id="eventForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="sportSelect">Спортивное событие:</label>
                <select name="sport" id="sportSelect" required>
                    <option value="Футбол">Футбол</option>
                    <option value="Баскетбол">Баскетбол</option>
                    <option value="Волейбол">Волейбол</option>
                </select>
            </div>
            <div class="form-group">
                <label for="eventPhoto">Фото мероприятия:</label>
                <input type="file" name="eventPhoto" id="eventPhoto">
            </div>
            <div class="form-group">
                <label for="eventDescription">Комментарий:</label>
                <textarea name="eventDescription" id="eventDescription"></textarea>
            </div>
            <div class="form-group">
                <label for="eventDate">Дата:</label>
                <input type="datetime-local" name="eventDate" id="eventDate" required>
            </div>
            <div class="form-group">
                <label for="eventLocation">Место:</label>
                <input type="text" name="eventLocation" id="eventLocation" required>
            </div>
            <div class="form-group">
                <label for="availableSpots">Доступные места:</label>
                <input type="number" name="availableSpots" id="availableSpots" required>
            </div>
            <button class="create-btn" type="submit" id="createEventButton">Создать</button>
        </form>
    </div>
    <?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $sport = $_POST['sport'];
    $description = $_POST['eventDescription'];
    $date = $_POST['eventDate'];
    $location = $_POST['eventLocation'];
    $availableSpots = $_POST['availableSpots'];

    // Получаем id пользователя из сессии
    $userId = $_SESSION["user_id"];

    // Обработка загрузки файла
    $targetDir = "../uploads/"; // Папка для сохранения загруженных файлов
    $targetFile = $targetDir . basename($_FILES["eventPhoto"]["name"]); // Полный путь к файлу
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Если файл не загружен, устанавливаем фото по умолчанию в зависимости от вида спорта
    if ($_FILES["eventPhoto"]["size"] == 0) {
        switch ($sport) {
            case 'Футбол':
                $targetFile = "../uploads/card-1.jpg";
                break;
            case 'Баскетбол':
                $targetFile = "../uploads/slide-2.jpg";
                break;
            case 'Волейбол':
                $targetFile = "../uploads/slide-3.jpg";
                break;
        }
        $uploadOk = 1; // Устанавливаем флаг успешной загрузки в true
    } else {
        // Проверка файла на допустимый формат (можно дополнительно добавить проверки)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Формат файла должен быть JPG, JPEG или PNG.";
            $uploadOk = 0;
        }
    }

    // Если все проверки пройдены, пытаемся загрузить файл на сервер
    if ($uploadOk == 1 && $_FILES["eventPhoto"]["size"] > 0) {
        if (move_uploaded_file($_FILES["eventPhoto"]["tmp_name"], $targetFile)) {
            echo "Файл " . basename($_FILES["eventPhoto"]["name"]) . " успешно загружен.";
        } else {
            echo "Ошибка при загрузке файла.";
            $uploadOk = 0; // Устанавливаем флаг успешной загрузки в false
        }
    }

    // Готовим SQL-запрос для вставки данных в таблицу
    $sql = "INSERT INTO `event` (sport, eventDescription, eventDate, eventLocation, eventSpots, user_id, eventImage) 
            VALUES ('$sport', '$description', '$date', '$location', '$availableSpots', '$userId', '$targetFile')";

    if ($con->query($sql) === TRUE && $uploadOk == 1) {
        // Выводим сообщение об успешном создании события с помощью JavaScript
        echo '<script>alert("Событие успешно создано");</script>';
        // Перенаправляем пользователя на главную страницу
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        echo '<script>alert("Упс! Что-то пошло не так(");</script>' . $con->error;
    }

    // Закрываем соединение с базой данных
    $con->close();
}
?>
</body>
</html>
