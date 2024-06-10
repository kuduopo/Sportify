<?php
require_once "../db_connect.php";

if (isset($_GET['organizer_id'])) {
    $organizer_id = $_GET['organizer_id'];

    $sql = "SELECT * FROM messages WHERE sender_id = $organizer_id OR receiver_id = $organizer_id ORDER BY timestamp ASC";
    $result = $con->query($sql);

    $messages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }

    echo json_encode(['messages' => $messages]);
}
?>
