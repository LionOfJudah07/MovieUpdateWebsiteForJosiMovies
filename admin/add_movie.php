<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$page_title = "Add Movie";
$error = '';
$success = '';

if (isset($_POST['add_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $episodes = (int)$_POST['episodes'];
    $duration = (int)$_POST['duration'];
    $release_year = (int)$_POST['release_year'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if (empty($title) || empty($genre)) {
        $error = "Title and genre are required";
    } elseif ($release_year < 1900 || $release_year > date('Y') + 1) {
        $error = "Invalid release year";
    } else {
        $poster = '';
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_types)) {
                if ($_FILES['poster']['size'] <= 5000000) { 
                    $poster = time() . '_' . $_FILES['poster']['name'];
                    move_uploaded_file($_FILES['poster']['tmp_name'], '../assets/uploads/' . $poster);
                } else {
                    $error = "File too large (max 5MB)";
                }
            } else {
                $error = "Invalid file type. Only JPG, PNG, GIF allowed";
            }
        }
        
        if (!$error) {
            $sql = "INSERT INTO movies (title, genre, type, episodes, duration, release_year, description, poster) 
                    VALUES ('$title', '$genre', '$type', $episodes, $duration, $release_year, '$description', '$poster')";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Movie added successfully!";
                write_log("Movie added: $title");
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<?php include 'includes/admin_header.php'; ?>

<h2 class="mb-4">Add New Movie or Series</h2>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Movie Details</h5>
    </div>
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title:*</label>
                        <input type="text" name="title" class="form-control" required placeholder="Enter movie title">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Genre:*</label>
                        <select name="genre" class="form-control" required>
                            <option value="">Select Genre</option>
                            <option value="Action">Action</option>
                            <option value="Drama">Drama</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Horror">Horror</option>
                            <option value="Romance">Romance</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Animation">Animation</option>
                            <option value="Documentary">Documentary</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Adventure">Adventure</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type:*</label>
                        <select name="type" class="form-control" required id="typeSelect">
                            <option value="Movie">Movie</option>
                            <option value="Series">Series</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="episodesField" style="display: none;">
                        <label class="form-label">Number of Episodes:*</label>
                        <input type="number" name="episodes" class="form-control" value="1" min="1" id="episodesInput">
                    </div>
                    
                    <div class="mb-3" id="durationField">
                        <label class="form-label">Duration (minutes):*</label>
                        <input type="number" name="duration" class="form-control" required min="1" id="durationInput">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Release Year:*</label>
                        <input type="number" name="release_year" class="form-control" required min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Poster Image (optional):</label>
                        <div class="input-group">
                            <input type="file" name="poster" class="form-control" accept="image/*" id="posterInput">
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> Max 500KB, JPG/PNG/GIF formats only
                        </div>
                        <div class="mt-2" id="imagePreview" style="display: none;">
                            <p class="mb-1">Preview:</p>
                            <img id="previewImage" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Image:</label>
                        <div class="alert alert-info">
                            <i class="fas fa-image"></i> No image selected. Upload an image to preview.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Enter movie description (plot, cast, etc.)"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" name="add_movie" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Movie/Series
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                    <a href="view_movies.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Movies
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
            document.getElementById('episodesInput').required = true;
            document.getElementById('durationInput').required = false;
        } else {
            document.getElementById('episodesField').style.display = 'none';
            document.getElementById('durationField').style.display = 'block';
            document.getElementById('episodesInput').required = false;
            document.getElementById('durationInput').required = true;
        }
    });
    
    document.getElementById('posterInput').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    
    
    document.querySelector('form').addEventListener('submit', function(e) {
        const type = document.getElementById('typeSelect').value;
        if (type === 'Series') {
            const episodes = document.getElementById('episodesInput').value;
            if (!episodes || episodes < 1) {
                e.preventDefault();
                alert('Please enter a valid number of episodes for the series.');
                document.getElementById('episodesInput').focus();
            }
        } else {
            const duration = document.getElementById('durationInput').value;
            if (!duration || duration < 1) {
                e.preventDefault();
                alert('Please enter a valid duration in minutes for the movie.');
                document.getElementById('durationInput').focus();
            }
        }
    });
</script>

<?php include 'includes/admin_footer.php'; ?>