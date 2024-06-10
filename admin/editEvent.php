<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

// Проверяем, передан ли идентификатор события
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Получаем информацию о событии из базы данных
    $sql_event = "SELECT * FROM event WHERE id = $event_id";
    $result_event = mysqli_query($con, $sql_event);

    if (mysqli_num_rows($result_event) == 1) {
        $event = mysqli_fetch_assoc($result_event);
    } else {
        echo "Событие не найдено.";
        exit;
    }
} else {
    echo "Идентификатор события не передан.";
    exit;
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка данных формы редактирования события
    $sport = $_POST["sport"];
    $eventDate = $_POST["eventDate"];
    $eventLocation = $_POST["eventLocation"];
    $eventImage = $_POST["eventImage"];
    $eventSpots = $_POST["eventSpots"];
    $eventDescription = $_POST["eventDescription"];

    // Обновляем информацию о событии в базе данных
    $sql_update_event = "UPDATE event SET 
                            sport = '$sport', 
                            eventDate = '$eventDate', 
                            eventLocation = '$eventLocation', 
                            eventImage = '$eventImage', 
                            eventSpots = '$eventSpots', 
                            eventDescription = '$eventDescription' 
                        WHERE id = $event_id";

    if (mysqli_query($con, $sql_update_event)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Ошибка при обновлении информации о событии: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать событие</title>
</head>
<body>
    <h2>Редактировать событие</h2>
    <form method="post">
        <label for="sport">Название события:</label><br>
        <input type="text" id="sport" name="sport" value="<?php echo $event['sport']; ?>"><br>

        <label for="eventDate">Дата события:</label><br>
        <input type="text" id="eventDate" name="eventDate" value="<?php echo $event['eventDate']; ?>"><br>

        <label for="eventLocation">Место:</label><br>
        <input type="text" id="eventLocation" name="eventLocation" value="<?php echo $event['eventLocation']; ?>"><br>

        <label for="eventImage">Изображение события:</label><br>
        <input type="text" id="eventImage" name="eventImage" value="<?php echo $event['eventImage']; ?>"><br>

        <label for="eventSpots">Количество мест:</label><br>
        <input type="number" id="eventSpots" name="eventSpots" value="<?php echo $event['eventSpots']; ?>"><br>

        <label for="eventDescription">Описание события:</label><br>
        <textarea id="eventDescription" name="eventDescription"><?php echo $event['eventDescription']; ?></textarea><br>

        <input type="submit" value="Сохранить">
    </form>
</body>
</html>
