<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Your Cart</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Your Cart</h2>

<?php
$total = 0;

if(!empty($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        echo "<p>".$item['name']." - ₹".$item['price']."</p>";
        $total += $item['price'];
    }

    echo "<h3>Total: ₹".$total."</h3>";
}else{
    echo "<p>Your cart is empty</p>";
}
?>

</body>
</html>