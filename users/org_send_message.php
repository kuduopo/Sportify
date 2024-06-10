
<?php
require_once "../db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $organizer_id = $_SESSION['user_id'];
    $user_id = $_POST['user_id']; // Предполагается, что это поле будет в форме
    $message = $con->real_escape_string($_POST['message']);

    $sql = "INSERT INTO `messages` (sender_id, receiver_id, message) VALUES ($organizer_id, $user_id, '$message')";

    if ($con->query($sql) === TRUE) {
        header("Location: organizer_chat.php?user_id=$user_id");
    } else {
        echo "Ошибка: " . $sql . "<br>" . $con->error;
    }
}
?>
