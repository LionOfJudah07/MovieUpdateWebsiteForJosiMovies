<?php
require_once 'config.php';

if (isset($_POST['movie_id'])) {
    $movie_id = (int)$_POST['movie_id'];
    
    if (!isset($_COOKIE['liked_' . $movie_id])) {
        $sql = "UPDATE movies SET likes = likes + 1 WHERE id = $movie_id";
        mysqli_query($conn, $sql);
        
        setcookie('liked_' . $movie_id, 'yes', time() + (86400 * 30), "/");
        write_log("Movie $movie_id received a like");
    }
}

header("Location: index.php");
exit();
?>