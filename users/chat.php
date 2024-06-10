<?php
require_once "../db_connect.php";
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$organizer_id = $_GET['organizer_id'];

// Проверяем, что организатор существует
$sql = "SELECT username FROM `user` WHERE user_id = $organizer_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $organizer = $result->fetch_assoc();
} else {
    echo "Организатор не найден";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chat.css">
    <title>Чат с <?php echo htmlspecialchars($organizer['username']); ?></title>
</head>
<body>
    <div class="chat-container" data-organizer-id="<?php echo $organizer_id; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
        <h2>Чат с <?php echo htmlspecialchars($organizer['username']); ?></h2>
        <div class="chat-box" id="chat-box">
            <!-- Сообщения будут загружены сюда -->
        </div>
        <form id="chat-form" action="send_message.php" method="POST">
            <input type="hidden" name="organizer_id" value="<?php echo $organizer_id; ?>">
            <input type="text" name="message" placeholder="Введите ваше сообщение" required>
            <button type="submit">Отправить</button>
        </form>
    </div>
    <script src="chat.js"></script>
</body>
</html>
