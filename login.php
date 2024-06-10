<?php
require "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка пользователя в базе данных
    $sql_user = "SELECT * FROM user WHERE email='$email'";
    $result_user = mysqli_query($con, $sql_user);

    // Проверка администратора в базе данных
    $sql_admin = "SELECT * FROM admin WHERE email='$email'";
    $result_admin = mysqli_query($con, $sql_admin);

    if (mysqli_num_rows($result_user) == 1) {
        $row = mysqli_fetch_assoc($result_user);
        if (password_verify($password, $row['password'])) {
            // Успешная авторизация пользователя
            session_start();
            $_SESSION['username'] = $row['username']; // Сохраняем имя пользователя в сессии
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: users/index.php"); // Переходим на страницу пользователя
            exit();
        } else {
            echo "Неверный пароль";
        }
    } elseif (mysqli_num_rows($result_admin) == 1) {
        $row = mysqli_fetch_assoc($result_admin);
        if (password_verify($password, $row['password'])) {
            // Успешная авторизация администратора
            session_start();
            $_SESSION['username'] = $row['username']; // Сохраняем имя администратора в сессии
            $_SESSION['admin_id'] = $row['admin_id'];
            header("Location: admin/index.php"); // Переходим на страницу администратора
            exit();
        } else {
            echo "Неверный пароль";
        }
    } else {
        echo "Такого пользователя не существует";
    }
}

mysqli_close($con);
?>
