    </div> 
    
    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> - <?php echo SITE_LOCATION; ?></p>
            <p>Phone: +251 911 223344 | Email: info@josimovies.com</p>
            
            <?php if (!isset($_COOKIE['visited'])): ?>
                <div class="alert alert-warning">
                    <form method="post" action="set_cookie.php">
                        <p>Welcome! We use cookies to improve your experience.</p>
                        <button type="submit" name="accept" class="btn btn-sm btn-primary">Accept</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>