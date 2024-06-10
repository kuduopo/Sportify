<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    // Если не авторизован, выполняем необходимые действия, например, перенаправляем на страницу авторизации
    header("Location: auth.html");
    exit();
}
?>
