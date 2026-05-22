<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$page_title = "Dashboard";

$movies_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM movies"))['count'];
$series_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM movies WHERE type = 'Series'"))['count'];
$customers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customers"))['count'];
$today_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customers WHERE DATE(created_at) = CURDATE()"))['count'];
?>
<?php include 'includes/admin_header.php'; ?>

<h2 class="mb-4">Dashboard Overview</h2>


<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, var(--primary-color), #c82333);">
            <div class="card-body text-center">
                <h5><i class="fas fa-film fa-2x mb-3"></i></h5>
                <h3 class="mb-2"><?php echo $movies_count; ?></h3>
                <p>Total Movies</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, var(--success-color), #1e7e34);">
            <div class="card-body text-center">
                <h5><i class="fas fa-tv fa-2x mb-3"></i></h5>
                <h3 class="mb-2"><?php echo $series_count; ?></h3>
                <p>Series</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, var(--warning-color), #e0a800);">
            <div class="card-body text-center">
                <h5><i class="fas fa-users fa-2x mb-3"></i></h5>
                <h3 class="mb-2"><?php echo $customers_count; ?></h3>
                <p>Total Customers</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, var(--info-color), #138496);">
            <div class="card-body text-center">
                <h5><i class="fas fa-user-plus fa-2x mb-3"></i></h5>
                <h3 class="mb-2"><?php echo $today_customers; ?></h3>
                <p>Today's Registrations</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activity Log</h5>
            </div>
            <div class="card-body">
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php
                    $log_file = '../logs/activity.log';
                    if (file_exists($log_file)) {
                        $logs = file($log_file);
                        if ($logs) {
                            echo '<div class="list-group">';
                            $recent_logs = array_slice($logs, -10); 
                            foreach (array_reverse($recent_logs) as $log) {
                                echo '<div class="list-group-item">' . htmlspecialchars(trim($log)) . '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="text-muted text-center py-3">No activity logged yet.</p>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">Activity log file not found.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>