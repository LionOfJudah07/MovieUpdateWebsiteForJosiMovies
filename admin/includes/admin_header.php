<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?><?php echo SITE_NAME; ?> - Admin</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
	<link rel="stylesheet" href="../assets/css/styles.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #343a40 !important;
        }
        .navbar-brand {
            color: #dc3545 !important;
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-bottom: 1px solid #495057;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a.active {
            background-color: #dc3545;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .admin-welcome {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        /* Admin-specific styles */
        .stats-card {
            border-radius: 10px;
            color: white;
            text-align: center;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .admin-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .admin-table thead {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-film"></i> <?php echo SITE_NAME; ?>
            </a>
            <span class="text-white">Owner Panel</span>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home"></i> View Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout (<?php echo $_SESSION['username']; ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="pt-3">
                    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="add_movie.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_movie.php' ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i> Add Movie/Series
                    </a>
                    <a href="view_movies.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_movies.php' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> View All Movies
                    </a>
                    <a href="view_customers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_customers.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> View Customers
                    </a>
                    <a href="../index.php" class="mt-4" style="background-color: #17a2b8;">
                        <i class="fas fa-external-link-alt"></i> Go to Public Site
                    </a>
                </div>
            </div>
            
            <div class="col-md-10 p-4">
                <div class="admin-welcome">
                    <h4><i class="fas fa-user-shield"></i> Welcome, <?php echo $_SESSION['username']; ?>!</h4>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <?php echo $_SESSION['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>