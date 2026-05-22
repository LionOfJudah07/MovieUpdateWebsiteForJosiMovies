<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT title, poster FROM movies WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $movie = mysqli_fetch_assoc($result);
    
    if ($movie['poster'] && file_exists('../assets/uploads/' . $movie['poster'])) {
        unlink('../assets/uploads/' . $movie['poster']);
    }
    
    $sql = "DELETE FROM movies WHERE id = $id";
    mysqli_query($conn, $sql);
    
    write_log("Movie deleted: " . $movie['title']);
    $_SESSION['message'] = "Movie deleted successfully!";
}

header("Location: view_movies.php");
exit();
?>