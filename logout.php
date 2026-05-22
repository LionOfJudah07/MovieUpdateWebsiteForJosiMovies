<?php
require_once 'config.php';

write_log("Admin logged out: " . $_SESSION['username']);

session_destroy();
header("Location: index.php");
exit();
?>