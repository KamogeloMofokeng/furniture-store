<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/website/init.php';
unset($_SESSION['SBUser']);
header('Location: login.php');

?>