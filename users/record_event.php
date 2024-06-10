<?php
session_start();
require_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Вы должны быть авторизованы для записи на событие.']);
        exit;
    }

    $eventId = $_POST["id"];
    $userId = $_SESSION["user_id"];

    $sql = "SELECT * FROM `event` WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eventSpots = $row["eventSpots"];
        if ($eventSpots > 0) {
            $check_sql = "SELECT * FROM `record` WHERE user_id = ? AND event_id = ?";
            $check_stmt = $con->prepare($check_sql);
            $check_stmt->bind_param("ii", $userId, $eventId);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            if ($check_result->num_rows == 0) {
                $insert_sql = "INSERT INTO `record` (user_id, event_id) VALUES (?, ?)";
                $insert_stmt = $con->prepare($insert_sql);
                $insert_stmt->bind_param("ii", $userId, $eventId);
                if ($insert_stmt->execute()) {
                    $eventSpots--;
                    $update_sql = "UPDATE `event` SET eventSpots = ? WHERE id = ?";
                    $update_stmt = $con->prepare($update_sql);
                    $update_stmt->bind_param("ii", $eventSpots, $eventId);
                    if ($update_stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Вы успешно записались на событие.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении количества мест: ' . $con->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при записи на событие: ' . $con->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Вы уже записаны на это событие.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Все места уже заняты!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Событие не найдено']);
    }
}
?>
