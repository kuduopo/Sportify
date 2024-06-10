<?php
session_start();
require_once "../db_connect.php"; // Подключаемся к базе данных
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Document</title>
</head>
<body>
<div id="sidebar">
    <div class="admin_name">
        <button class="btn__admin" id="dropdownMenuButton"><?php echo $_SESSION['username'] ?></button>
        <a class="admin_name-a" href="../logout.php"><img src="../images/exit.svg" alt=""></a>
    </div>

    <ul>
        <li><a href="#" onclick="openTab(event, 'tab1')">Пользователи</a></li>
        <li><a href="#" onclick="openTab(event, 'tab2')">События</a></li>
        <li><a href="#" onclick="openTab(event, 'tab3')">История посещений</a></li>
    </ul>
</div>

<div id="content">
    <div id="welcome" class="tabcontent active">
        <h2>Добро пожаловать в панель администратора!</h2>
        <p></p>
    </div>

    <div id="tab1" class="tabcontent">
        <h2>Пользователи</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Действия</th>
            </tr>
            <?php
            // Запрос к базе данных для получения всех пользователей
            $sql_users = "SELECT * FROM user";
            $result_users = mysqli_query($con, $sql_users);

            if (mysqli_num_rows($result_users) > 0) {
                while ($row = mysqli_fetch_assoc($result_users)) {
                    echo "<tr>";
                    echo "<td>".$row['user_id']."</td>";
                    echo "<td>".$row['username']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td><div class='div_action'><a href='editUser.php?id=".$row['user_id']."'><div class='div_edit'><img class='img_action' src='../images/edit.svg'></div></a> <a href='deleteUser.php?id=".$row['user_id']."' onclick='return confirmDelete()'><div class='div_delete'><img class='img_action' src='../images/delete.svg'></div></a></div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Пользователи не найдены</td></tr>";
            }
            ?>
        </table>
    </div>
  
    <div id="tab2" class="tabcontent">
        <h2>События</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Название события</th>
                <th>Дата события</th>
                <th>Место</th>
                <th>Изображение события</th>
                <th>Количество мест</th>
                <th>Описание события</th>
                <th>Имя пользователя</th>
                <th>Email пользователя</th>
                <th>Действия</th>
            </tr>
            <?php
            // Запрос к базе данных для получения всех событий и имен пользователей, которые их создали
            $sql_events = "SELECT e.*, u.username, u.email FROM event e JOIN user u ON e.user_id = u.user_id";
            $result_events = mysqli_query($con, $sql_events);

            if (mysqli_num_rows($result_events) > 0) {
                while ($row = mysqli_fetch_assoc($result_events)) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['sport']."</td>";
                    echo "<td>".$row['eventDate']."</td>";
                    echo "<td>".$row['eventLocation']."</td>";
                    echo "<td>".$row['eventImage']."</td>";
                    echo "<td>".$row['eventSpots']."</td>";
                    echo "<td>".$row['eventDescription']."</td>";
                    echo "<td>".$row['username']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td><div class='div_action'><a href='editEvent.php?id=".$row['id']."'><div class='div_edit'><img class='img_action' src='../images/edit.svg'></div></a> <a href='deleteEvent.php?id=".$row['id']."' onclick='return confirmDelete()'><div class='div_delete'><img class='img_action' src='../images/delete.svg'></div></a></div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>События не найдены</td></tr>";
            }
            ?>
        </table>
    </div>

    <div id="tab3" class="tabcontent">
        <h2>История посещений</h2>
        <table>
            <tr>
                <th>ID события</th>
                <th>Название события</th>
                <th>Дата события</th>
                <th>Место</th>
                <th>Имя пользователя</th>
                <th>Email пользователя</th>
                <th>Действия</th>
            </tr>
            <?php
            // Запрос к базе данных для получения истории посещений всех пользователей
            $sql_history = "SELECT r.event_id, e.sport, e.eventDate, e.eventLocation, u.username, u.email 
                            FROM record r 
                            JOIN event e ON r.event_id = e.id 
                            JOIN user u ON r.user_id = u.user_id";
            $result_history = mysqli_query($con, $sql_history);

            if (mysqli_num_rows($result_history) > 0) {
                while ($row = mysqli_fetch_assoc($result_history)) {
                    echo "<tr>";
                    echo "<td>".$row['event_id']."</td>";
                    echo "<td>".$row['sport']."</td>";
                    echo "<td>".$row['eventDate']."</td>";
                    echo "<td>".$row['eventLocation']."</td>";
                    echo "<td>".$row['username']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td><div class='div_action'><a href='editRecord.php?id=".$row['event_id']."'><div class='div_edit'><img class='img_action' src='../images/edit.svg'></div></a> <a href='deleteRecord.php?id=".$row['event_id']."' onclick='return confirmDelete()'><div class='div_delete'><img class='img_action' src='../images/delete.svg'></div></a></div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>История посещений не найдена</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByTagName("a");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    document.getElementById("welcome").classList.add("active"); // Активирует первую вкладку по умолчанию

    function confirmDelete() {
        return confirm("Вы уверены, что хотите удалить это событие?");
    }
</script>
</body>
</html>

