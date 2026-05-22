<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$page_title = "Edit Movie";
$error = '';
$success = '';

$id = (int)$_GET['id'];
$sql = "SELECT * FROM movies WHERE id = $id";
$result = mysqli_query($conn, $sql);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    header("Location: view_movies.php");
    exit();
}

if (isset($_POST['update_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $episodes = (int)$_POST['episodes'];
    $duration = (int)$_POST['duration'];
    $release_year = (int)$_POST['release_year'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $poster = $movie['poster'];
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_types)) {
            if ($_FILES['poster']['size'] <= 5000000) {

                if ($poster && file_exists('../assets/uploads/' . $poster)) {
                    unlink('../assets/uploads/' . $poster);
                }
                
                $poster = time() . '_' . $_FILES['poster']['name'];
                move_uploaded_file($_FILES['poster']['tmp_name'], '../assets/uploads/' . $poster);
            } else {
                $error = "File too large (max 500KB)";
            }
        } else {
            $error = "Invalid file type";
        }
    }
    
    if (!$error) {
        $sql = "UPDATE movies SET 
                title = '$title',
                genre = '$genre',
                type = '$type',
                episodes = $episodes,
                duration = $duration,
                release_year = $release_year,
                description = '$description',
                poster = '$poster'
                WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Movie updated successfully!";
            write_log("Movie updated: $title");
            
            $result = mysqli_query($conn, "SELECT * FROM movies WHERE id = $id");
            $movie = mysqli_fetch_assoc($result);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<?php include 'includes/admin_header.php'; ?>

<h2 class="mb-4">Edit Movie: <?php echo $movie['title']; ?></h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Movie Details</h5>
    </div>
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title:*</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Genre:*</label>
                        <select name="genre" class="form-control" required>
                            <option value="Action" <?php echo $movie['genre'] == 'Action' ? 'selected' : ''; ?>>Action</option>
                            <option value="Drama" <?php echo $movie['genre'] == 'Drama' ? 'selected' : ''; ?>>Drama</option>
                            <option value="Comedy" <?php echo $movie['genre'] == 'Comedy' ? 'selected' : ''; ?>>Comedy</option>
                            <option value="Horror" <?php echo $movie['genre'] == 'Horror' ? 'selected' : ''; ?>>Horror</option>
                            <option value="Romance" <?php echo $movie['genre'] == 'Romance' ? 'selected' : ''; ?>>Romance</option>
                            <option value="Sci-Fi" <?php echo $movie['genre'] == 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
                            <option value="Thriller" <?php echo $movie['genre'] == 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                            <option value="Animation" <?php echo $movie['genre'] == 'Animation' ? 'selected' : ''; ?>>Animation</option>
                            <option value="Documentary" <?php echo $movie['genre'] == 'Documentary' ? 'selected' : ''; ?>>Documentary</option>
                            <option value="Fantasy" <?php echo $movie['genre'] == 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type:*</label>
                        <select name="type" class="form-control" required id="typeSelect">
                            <option value="Movie" <?php echo $movie['type'] == 'Movie' ? 'selected' : ''; ?>>Movie</option>
                            <option value="Series" <?php echo $movie['type'] == 'Series' ? 'selected' : ''; ?>>Series</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="episodesField" style="display: <?php echo $movie['type'] == 'Series' ? 'block' : 'none'; ?>">
                        <label class="form-label">Episodes:*</label>
                        <input type="number" name="episodes" class="form-control" value="<?php echo $movie['episodes']; ?>" min="1">
                    </div>
                    
                    <div class="mb-3" id="durationField" style="display: <?php echo $movie['type'] == 'Movie' ? 'block' : 'none'; ?>">
                        <label class="form-label">Duration (minutes):*</label>
                        <input type="number" name="duration" class="form-control" value="<?php echo $movie['duration']; ?>" min="1">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Release Year:*</label>
                        <input type="number" name="release_year" class="form-control" value="<?php echo $movie['release_year']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Poster:</label><br>
                        <?php if (!empty($movie['poster'])): ?>
                            <img src="../assets/uploads/<?php echo $movie['poster']; ?>" class="img-thumbnail mb-2" style="max-height: 200px; max-width: 100%;">
                            <p class="text-muted small"><?php echo $movie['poster']; ?></p>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No poster uploaded
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload New Poster (optional):</label>
                        <input type="file" name="poster" class="form-control" accept="image/*">
                        <div class="form-text">Max 500KB, JPG/PNG/GIF only. Leave empty to keep current image.</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Description:*</label>
                        <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" name="update_movie" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Movie
                    </button>
                    <a href="view_movies.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Movies
                    </a>
                    <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-danger float-end" onclick="return confirm('Are you sure you want to delete this movie?')">
                        <i class="fas fa-trash"></i> Delete Movie
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('typeSelect').addEventListener('change', function() {
    if (this.value === 'Series') {
        document.getElementById('episodesField').style.display = 'block';
        document.getElementById('durationField').style.display = 'none';
        document.querySelector('[name="episodes"]').required = true;
        document.querySelector('[name="duration"]').required = false;
    } else {
        document.getElementById('episodesField').style.display = 'none';
        document.getElementById('durationField').style.display = 'block';
        document.querySelector('[name="episodes"]').required = false;
        document.querySelector('[name="duration"]').required = true;
    }
});
</script>

<?php include 'includes/admin_footer.php'; ?>