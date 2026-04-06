<?php
require 'config.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=Please+login+to+use+the+cart&type=danger");
    exit();
}

$user_id = $_SESSION['user_id'];
$action  = $_GET['action'] ?? '';
$prod_id = (int)($_GET['id'] ?? 0);

if ($action === 'add' && $prod_id > 0) {
    // Check product exists
    $check = mysqli_query($conn, "SELECT id FROM products WHERE id = $prod_id");
    if (mysqli_num_rows($check) === 0) {
        header("Location: index.php");
        exit();
    }

    // Check if already in cart
    $exists = mysqli_query($conn,
        "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $prod_id"
    );

    if (mysqli_num_rows($exists) > 0) {
        // Increase quantity
        $row = mysqli_fetch_assoc($exists);
        $new_qty = $row['quantity'] + 1;
        mysqli_query($conn, "UPDATE cart SET quantity = $new_qty WHERE id = {$row['id']}");
    } else {
        // Add new cart row
        mysqli_query($conn,
            "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $prod_id, 1)"
        );
    }

    header("Location: index.php?added=1");
    exit();

} elseif ($action === 'remove' && $prod_id > 0) {
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id AND id = $prod_id");
    header("Location: veiw_cart.php?removed=1");
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>
