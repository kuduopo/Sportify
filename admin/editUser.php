<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

// Проверяем, передан ли идентификатор пользователя
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Получаем информацию о пользователе из базы данных
    $sql_user = "SELECT * FROM user WHERE user_id = $user_id";
    $result_user = mysqli_query($con, $sql_user);

    if (mysqli_num_rows($result_user) == 1) {
        $user = mysqli_fetch_assoc($result_user);
    } else {
        echo "Пользователь не найден.";
        exit;
    }
} else {
    echo "Идентификатор пользователя не передан.";
    exit;
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка данных формы редактирования пользователя
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Обновляем информацию о пользователе в базе данных
    $sql_update_user = "UPDATE user SET 
                            username = '$username', 
                            email = '$email' 
                        WHERE user_id = $user_id";

    if (mysqli_query($con, $sql_update_user)) {
        header("Location: index.php");
    } else {
        echo "Ошибка при обновлении информации о пользователе: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать пользователя</title>
</head>
<body>
    <h2>Редактировать пользователя</h2>
    <form method="post">
        <label for="username">Имя пользователя:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br>

        <input type="submit" value="Сохранить">
    </form>
</body>
</html>
