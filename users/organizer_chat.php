<?php
require_once "../db_connect.php";
require "header_users.php";


// Проверяем, авторизован ли организатор
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.html");
    exit();
}

$organizer_id = $_SESSION['user_id'];

// Получаем список пользователей, с которыми есть сообщения
$sql_users = "SELECT DISTINCT u.user_id, u.username 
              FROM user AS u 
              JOIN messages AS m 
              ON u.user_id = m.sender_id OR u.user_id = m.receiver_id 
              WHERE m.sender_id = $organizer_id OR m.receiver_id = $organizer_id";
$result_users = $con->query($sql_users);

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Получаем сообщения с пользователем
    $sql_messages = "SELECT * FROM messages 
                     WHERE (sender_id = $organizer_id AND receiver_id = $user_id) 
                     OR (sender_id = $user_id AND receiver_id = $organizer_id) 
                     ORDER BY timestamp ASC";
    $result_messages = $con->query($sql_messages);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chat.css">
    <title>Чат с пользователями</title>
</head>
<body>
    <div class="chat-container">
        <h2>Чат с пользователями</h2>
        <div class="users-list">
            <h3>Пользователи:</h3>
            <ul>
                <?php while ($user = $result_users->fetch_assoc()) { ?>
                    <li>
                        <a href="?user_id=<?php echo $user['user_id']; ?>">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php if (isset($user_id)) { ?>
        <div class="chat-box" id="chat-box">
            <?php while ($message = $result_messages->fetch_assoc()) { ?>
                <div class="message <?php echo $message['sender_id'] == $organizer_id ? 'sent' : 'received'; ?>">
                    <?php echo htmlspecialchars($message['message']); ?>
                </div>
            <?php } ?>
        </div>
        <form action="org_send_message.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <textarea name="message" placeholder="Введите ваше сообщение" required></textarea>
            <button type="submit">Отправить сообщение</button>
        </form>
        <?php } ?>
    </div>
    <script src="chat.js"></script>
</body>
</html>
