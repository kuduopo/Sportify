<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

// Проверяем, передан ли идентификатор записи
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Получаем информацию о записи из базы данных
    $sql_record = "SELECT * FROM record WHERE id = $record_id";
    $result_record = mysqli_query($con, $sql_record);

    if (mysqli_num_rows($result_record) == 1) {
        $record = mysqli_fetch_assoc($result_record);
    } else {
        echo "Запись не найдена.";
        exit;
    }
} else {
    echo "Идентификатор записи не передан.";
    exit;
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка данных формы редактирования записи
    $event_id = $_POST["event_id"];
    $user_id = $_POST["user_id"];

    // Обновляем информацию о записи в базе данных
    $sql_update_record = "UPDATE record SET 
                            event_id = '$event_id', 
                            user_id = '$user_id'
                        WHERE id = $record_id";

    if (mysqli_query($con, $sql_update_record)) {
        header("Location: index.php");
    } else {
        echo "Ошибка при обновлении информации о записи: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать запись</title>
</head>
<body>
    <h2>Редактировать запись</h2>
    <form method="post">
        <label for="event_id">ID события:</label><br>
        <input type="text" id="event_id" name="event_id" value="<?php echo $record['event_id']; ?>"><br>

        <label for="user_id">ID пользователя:</label><br>
        <input type="text" id="user_id" name="user_id" value="<?php echo $record['user_id']; ?>"><br>

        <input type="submit" value="Сохранить">
    </form>
</body>
</html>
