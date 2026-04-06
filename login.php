<?php
require 'config.php';

// If already logged in, go home
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: login.html?msg=Please+fill+all+fields&type=danger");
        exit();
    }

    // Check user in database
    $email_safe = mysqli_real_escape_string($conn, $email);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email_safe'");

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login success — set session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];

            header("Location: index.php");
            exit();
        } else {
            header("Location: login.html?msg=Incorrect+password.+Please+try+again.&type=danger");
            exit();
        }
    } else {
        header("Location: login.html?msg=No+account+found+with+that+email.+Please+sign+up.&type=danger");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
