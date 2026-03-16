<?php
session_start();

// Agar cart session set nahi hai toh create karo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   ADD TO CART
========================= */
if (isset($_POST['add_to_cart'])) {

    $name  = $_POST['car_name'];
    $price = $_POST['price'];

    $item_exists = false;

    // Check karo item already cart me hai ya nahi
    foreach ($_SESSION['cart'] as $key => $item) {

        if ($item['name'] == $name) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            $item_exists = true;
            break;
        }
    }

    // Agar item cart me nahi hai
    if (!$item_exists) {
        $_SESSION['cart'][] = [
            "name" => $name,
            "price" => $price,
            "quantity" => 1
        ];
    }

    header("Location: index.php");
    exit();
}

/* =========================
   REMOVE ITEM
========================= */
if (isset($_GET['remove'])) {

    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);

    // Reindex array
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: view_cart.php");
    exit();
}

/* =========================
   CLEAR CART
========================= */
if (isset($_GET['clear'])) {

    unset($_SESSION['cart']);
    header("Location: view_cart.php");
    exit();
}
?>