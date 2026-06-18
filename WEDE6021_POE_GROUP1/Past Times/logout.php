<?php
session_start();

// clear session completely
$_SESSION = [];
session_unset();
session_destroy();

// force redirect
header("Location: home.php");
exit();