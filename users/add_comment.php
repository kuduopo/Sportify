<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $organizer_id = $_POST['organizer_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO comments (user_id, organizer_id, comment) VALUES ('$user_id', '$organizer_id', '$comment')";

    if ($con->query($sql) === TRUE) {
        header("Location: organizer.php?user_id=$organizer_id");
    } else {
        echo "Ошибка при добавлении комментария: " . $con->error;
    }
}
?>
