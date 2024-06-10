<?php
require_once "../db_connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $organizer_id = $_POST['organizer_id'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO `ratings` (user_id, organizer_id, rating) VALUES ('$user_id', '$organizer_id', '$rating')";
    if ($con->query($sql) === TRUE) {
        header("Location: organizer.php?user_id=$organizer_id");
    } else {
        echo "Ошибка: " . $con->error;
    }
} else {
    echo "Вы должны быть авторизованы, чтобы оставить оценку.";
}
?>
