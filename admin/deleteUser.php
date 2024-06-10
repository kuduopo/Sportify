<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

// Проверяем, передан ли идентификатор пользователя
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Удаляем пользователя из базы данных
    $sql_delete_user = "DELETE FROM user WHERE user_id = $user_id";

    if (mysqli_query($con, $sql_delete_user)) {
        header("Location: index.php");
    } else {
        echo "Ошибка при удалении пользователя: " . mysqli_error($con);
    }
} else {
    echo "Идентификатор пользователя не передан.";
}
?>
