<?php
session_start();

require('mysqli.php');

if($_GET['cmd'] === 0)
	die(isset($_SESSION['id']) && isset($_SESSION['password']));
?>