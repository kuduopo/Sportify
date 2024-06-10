<?php
require_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL запрос для удаления события из базы данных
    $sql_delete_event = "DELETE FROM `event` WHERE id=$id";

    if (mysqli_query($con, $sql_delete_event)) {
        // После успешного удаления перенаправьте пользователя обратно на страницу администратора
        header("Location: index.php");
        exit();
    } else {
        echo "Ошибка при удалении события: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
