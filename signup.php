<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        header("Location: signup.html?msg=Please+fill+all+fields&type=danger");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: signup.html?msg=Password+must+be+at+least+6+characters&type=danger");
        exit();
    }

    if ($password !== $confirm) {
        header("Location: signup.html?msg=Passwords+do+not+match&type=danger");
        exit();
    }

    // Check email already exists
    $email_safe = mysqli_real_escape_string($conn, $email);
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_safe'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: signup.html?msg=This+email+is+already+registered.+Please+login.&type=danger");
        exit();
    }

    // Check username exists
    $uname_safe = mysqli_real_escape_string($conn, $username);
    $check2 = mysqli_query($conn, "SELECT id FROM users WHERE username = '$uname_safe'");
    if (mysqli_num_rows($check2) > 0) {
        header("Location: signup.html?msg=Username+already+taken.+Choose+another.&type=danger");
        exit();
    }

    // Hash password and insert
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $result = mysqli_query($conn,
        "INSERT INTO users (username, email, password) VALUES ('$uname_safe', '$email_safe', '$hashed')"
    );

    if ($result) {
        header("Location: login.html?msg=Account+created+successfully!+Please+login.&type=success");
        exit();
    } else {
        header("Location: signup.html?msg=Something+went+wrong.+Please+try+again.&type=danger");
        exit();
    }
} else {
    header("Location: signup.html");
    exit();
}
?>
