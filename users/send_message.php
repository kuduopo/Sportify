<?php
require_once "../db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['organizer_id'];
    $message = $con->real_escape_string($_POST['message']);

    $sql = "INSERT INTO `messages` (sender_id, receiver_id, message) VALUES ($sender_id, $receiver_id, '$message')";

    if ($con->query($sql) === TRUE) {
        header("Location: chat.php?user_id=$user_id");
    } else {
        echo "Ошибка: " . $sql . "<br>" . $con->error;
    }
}
?>

