<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$page_title = "View Movies";
?>
<?php include 'includes/admin_header.php'; ?>


    <div class="col-md-9">
        <h2>All Movies & Series</h2>
        
        <?php
        $sql = "SELECT * FROM movies ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered">';
            echo '<tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Likes</th>
                    <th>Actions</th>
                  </tr>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['genre'] . '</td>';
                echo '<td>' . $row['type'] . '</td>';
                echo '<td>' . $row['release_year'] . '</td>';
                echo '<td>' . $row['likes'] . '</td>';
                echo '<td>
                        <a href="edit_movie.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_movie.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this movie?\')">Delete</a>
                      </td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<div class="alert alert-info">No movies found.</div>';
        }
        ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>