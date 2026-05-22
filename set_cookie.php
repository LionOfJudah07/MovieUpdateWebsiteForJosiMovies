<?php
require_once '../config.php';

if (isset($_POST['accept'])) {
    setcookie("visited", "yes", time() + (86400 * 30), "/");
    write_log("Cookie accepted by visitor");
    header("Location: index.php");
    exit();
}
?>