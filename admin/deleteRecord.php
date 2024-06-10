<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

// Проверяем, передан ли идентификатор записи
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Удаляем запись из базы данных
    $sql_delete_record = "DELETE FROM record WHERE id = $record_id";

    if (mysqli_query($con, $sql_delete_record)) {
        header("Location: index.php");
    } else {
        echo "Ошибка при удалении записи: " . mysqli_error($con);
    }
} else {
    echo "Идентификатор записи не передан.";
}
?>
