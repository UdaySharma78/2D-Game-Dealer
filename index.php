<?php
session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['add_to_cart'])){
    $item = [
        "name" => $_POST['name'],
        "price" => $_POST['price']
    ];

    $_SESSION['cart'][] = $item;

    header("Location: index.php?msg=added");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>2D Car Dealer | Premium Toy Cars</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="logo">🚗 2D Car Dealer</div>
    <nav>
        <a href="#">Home</a>
        <a href="view_cart.php">Cart (<?php echo count($_SESSION['cart']); ?>)</a>
        <a href="login.php">Login</a>
        <div class="game-nav">
            <a href="game.html">Test Drive</a>
            <span class="info-icon" onclick="showGameInfo()">?</span>
        </div>
        <a href="about.html">About</a>
    </nav>
</header>

<?php if(isset($_GET['msg']) && $_GET['msg']=="added"): ?>
<div class="success-msg">Item Added To Cart ✅</div>
<?php endif; ?>

<section class="hero">
    <div>
        <h1>Premium Hot Wheels Collection</h1>
        <p>Experience Speed. Collect Luxury. Drive the Dream.</p>
        <button class="shop-btn" onclick="scrollToProducts()">Shop Now</button>
    </div>
</section>

<section class="products" id="productSection">

<?php
$cars = [
["Porsche 911 GT3 RS",178,"Z/porshe.jpg"],
["'70 Dodge Charger R/T",182,"Z/dodge.jpg"],
["Toyota Supra (Fast & Furious)",162,"Z/supra.webp"],
["BMW M4",190,"Z/bmw.jpg"],
["Bugatti Chiron",210,"Z/bugatti.webp"],
["Ferrari SF90 Stradale",220,"Z/ferrari.webp"],
["Lamborghini Aventador",205,"Z/lamborgini.webp"],
["Ford Mustang Shelby GT500",195,"Z/mustang.webp"],
["Mercedes-AMG G63",230,"Z/gwagon.jpg"],
["2018 Bentley Continental GT3",215,"Z/bentley.jpg"],
["KICK Sauber F1 Team (2024)",240,"Z/formula1.webp"],
["Audi 90 Quattro IMSA",185,"Z/audii.jpg"],
["Pagani Huayra",260,"Z/pagani.webp"],
["Hot Wheels Stunt Track Pack (5 Cars)",499,"Z/pack2.webp"],
["HW Retro Racers Pack (5 Cars)",520,"Z/pack3.jpg"],
["Monster Truck",150,"Z/monster truck.webp"]
];

foreach($cars as $index=>$car){
    $hidden = $index > 3 ? "hidden" : "";
    echo '
    <div class="product-card '.$hidden.'">
        <img src="'.$car[2].'">
        <h3>'.$car[0].'</h3>
        <p class="price">₹'.$car[1].'</p>
        <div class="buttons">
            <button class="buy-btn">Buy Now</button>
            <form method="POST">
                <input type="hidden" name="name" value="'.$car[0].'">
                <input type="hidden" name="price" value="'.$car[1].'">
                <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>';
}
?>

</section>

<div class="see-more-container">
    <button onclick="showMore()" id="showMoreBtn" class="see-more-btn">See More</button>
    <button onclick="showLess()" id="showLessBtn" class="see-more-btn" style="display:none;">Show Less</button>
</div>

<footer>
    <p>© 2026 2D Car Dealer | Final Year Project</p>
</footer>

<script>
function showMore() {
    document.querySelectorAll(".hidden").forEach(card => {
        card.style.display = "block";
    });
    document.getElementById("showMoreBtn").style.display = "none";
    document.getElementById("showLessBtn").style.display = "inline-block";
}

function showLess() {
    document.querySelectorAll(".hidden").forEach(card => {
        card.style.display = "none";
    });
    document.getElementById("showMoreBtn").style.display = "inline-block";
    document.getElementById("showLessBtn").style.display = "none";
    scrollToProducts();
}

function scrollToProducts(){
    document.getElementById("productSection").scrollIntoView({behavior:"smooth"});
}

function showGameInfo(){
    alert("Play our Car Test Drive Challenge! Beat the high score and win an exclusive discount coupon.");
}
</script>

</body>
</html>