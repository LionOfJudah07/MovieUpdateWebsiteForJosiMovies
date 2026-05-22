<?php
require_once '../config.php';

if (!is_admin_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$page_title = "View Customers";
?>
<?php include 'includes/admin_header.php'; ?>

    <div class="col-md-9">
        <h2>Registered Customers</h2>
        
        <?php
        $sql = "SELECT * FROM customers ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered">';
            echo '<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered On</th>
                  </tr>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['phone'] . '</td>';
                echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            echo '<h4 class="mt-5">Activity Log</h4>';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<pre style="height: 200px; overflow-y: scroll;">';
            if (file_exists('../logs/activity.log')) {
                echo file_get_contents('../logs/activity.log');
            } else {
                echo "No log file found.";
            }
            echo '</pre>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">No customers registered yet.</div>';
        }
        ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>