<?php
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    
    if (empty($name) || empty($email)) {
        $error = "Name and email are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
   
        $check_sql = "SELECT * FROM customers WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered";
        } else {
            $sql = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Registration successful!";
                write_log("New customer registered: $email");
                header("Location: index.php");
                exit();
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}

header("Location: index.php");
exit();
?>