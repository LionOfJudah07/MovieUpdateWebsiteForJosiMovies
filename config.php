<?php
session_start();

define('SITE_NAME', 'Josi Movies');
define('SITE_LOCATION', 'Addis Ababa, 22 Mazoria');

if (!file_exists('logs')) {
    mkdir('logs', 0777, true);
}

require_once 'includes/db_connection.php';

function write_log($message) {
    $log_file = 'logs/activity.log';
    $log_message = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}
