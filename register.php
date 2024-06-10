<?php
require "db_connect.php";

// Обработка данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка наличия пользователя с таким же email
    $check_sql = "SELECT * FROM user WHERE email='$email'";
    $check_result = mysqli_query($con, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo "Пользователь с таким email уже существует";
    } else {
        // Хеширование пароля (лучше использовать более безопасные методы)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL запрос для вставки данных
        $insert_sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if (mysqli_query($con, $insert_sql)) {
            echo "Успешная регистрация!";
        } else {
            echo "Ошибка: " . $insert_sql . "<br>" . mysqli_error($con);
        }
    }
}

mysqli_close($con);
?>
