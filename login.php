<?php
require_once 'config.php';
$page_title = "Admin Login";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        write_log("Admin logged in: " . $username);
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card mt-5">
            <div class="card-header">
                <h4>Admin Login</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label>Username:</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Password:</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                <span id="toggleIcon">👁️</span>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    var passwordInput = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}
</script>

<?php include 'includes/footer.php'; ?>