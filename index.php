<?php
require_once 'config.php';
$page_title = "Home";
?>
<?php include 'includes/header.php'; ?>
<head>
<link rel="stylesheet" href="assets/css/styles.css">

</head>
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Latest Movies & Series</h2>
        
        <?php
        
        $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        
        if ($genre) {
            $sql = "SELECT * FROM movies WHERE genre = '$genre' ORDER BY created_at DESC";
            echo "<h4>Genre: $genre</h4>";
        } elseif ($type == 'Series') {
            $sql = "SELECT * FROM movies WHERE type = 'Series' ORDER BY created_at DESC";
            echo "<h4>All Series</h4>";
        } else {
            $sql = "SELECT * FROM movies ORDER BY created_at DESC LIMIT 12";
        }
        
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="row">';
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-3 mb-4">
                    <div class="movie-card">
                        <?php if (!empty($row['poster'])): ?>
                            <img src="assets/uploads/<?php echo $row['poster']; ?>" class="movie-poster" alt="<?php echo $row['title']; ?>">
                        <?php else: ?>
                            <div class="movie-poster bg-secondary text-white d-flex align-items-center justify-content-center">
                                No Image
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="mt-2"><?php echo $row['title']; ?></h5>
                        <span class="genre-badge"><?php echo $row['genre']; ?></span>
                        
                        <?php if ($row['type'] == 'Series'): ?>
                            <p class="mb-1"><small>Episodes: <?php echo $row['episodes']; ?></small></p>
                        <?php else: ?>
                            <p class="mb-1"><small>Duration: <?php echo $row['duration']; ?> min</small></p>
                        <?php endif; ?>
                        
                        <p class="mb-1"><small>Year: <?php echo $row['release_year']; ?></small></p>
                        <p class="mb-1"><small>Likes: <?php echo $row['likes']; ?></small></p>
                        
                        <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#movieModal<?php echo $row['id']; ?>">
                            View Details
                        </button>
                    </div>
                </div>
                
             
                <div class="modal fade" id="movieModal<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo $row['title']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Genre:</strong> <?php echo $row['genre']; ?></p>
                                <p><strong>Type:</strong> <?php echo $row['type']; ?></p>
                                <?php if ($row['type'] == 'Series'): ?>
                                    <p><strong>Episodes:</strong> <?php echo $row['episodes']; ?></p>
                                <?php else: ?>
                                    <p><strong>Duration:</strong> <?php echo $row['duration']; ?> minutes</p>
                                <?php endif; ?>
                                <p><strong>Year:</strong> <?php echo $row['release_year']; ?></p>
                                <p><strong>Description:</strong></p>
                                <p><?php echo $row['description']; ?></p>
                                <p><strong>Likes:</strong> <?php echo $row['likes']; ?></p>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="like_movie.php">
                                    <input type="hidden" name="movie_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-thumbs-up"></i> Like
                                    </button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">No movies found.</div>';
        }
        ?>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <h4>Loyal Customer Registration</h4>
        <form method="post" action="register.php" class="bg-white p-4 rounded">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>