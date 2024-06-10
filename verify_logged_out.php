<?php
	session_start();
	
	if(empty($_SESSION['type']));
	else if(strcmp($_SESSION['type'], "admin") == 0)
		header("Location: ../admin/home.php");
	else if(strcmp($_SESSION['type'], "user") == 0)
		header("Location: ../users/index.php");
?>