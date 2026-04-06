<?php
require 'config.php';

// Get cart count for logged-in user
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $r = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id = $uid");
    $row = mysqli_fetch_assoc($r);
    $cart_count = $row['total'] ? (int)$row['total'] : 0;
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2D Car Dealer - Premium Toy Cars</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="brand">2D <span>Car Dealer</span></a>
    <ul class="nav-links">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="game.php">🎮 Test Drive</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li>
                <a href="veiw_cart.php" class="cart-link">
                    🛒 Cart <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><span class="username-display">👤 <?= htmlspecialchars($_SESSION['username']) ?></span></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html" class="btn btn-primary" style="padding:8px 16px;font-size:0.85rem;">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- HERO -->
<section class="hero">
    <h1>Premium <span>Toy Cars</span> Collection</h1>
    <p>Explore the world's most iconic supercars — Audi, Ferrari, Lamborghini, Bugatti and more. Play our racing game to win exclusive discount coupons!</p>
    <div class="hero-btns">
        <a href="#products" class="btn btn-primary">🚗 Browse Cars</a>
        <a href="game.php" class="btn btn-secondary">🎮 Test Drive & Win Coupons</a>
    </div>
</section>

<!-- LOGIN REMINDER BANNER -->
<?php if (!isset($_SESSION['user_id'])): ?>
<div style="background:rgba(245,158,11,0.08);border-top:1px solid rgba(245,158,11,0.2);border-bottom:1px solid rgba(245,158,11,0.2);padding:14px 30px;text-align:center;">
    <span style="color:#94a3b8;">👋 Want to add cars to your cart or win discount coupons?</span>
    <a href="login.html" style="color:#f59e0b;font-weight:700;margin-left:8px;text-decoration:none;">Login</a>
    <span style="color:#475569;margin:0 8px;">or</span>
    <a href="signup.html" style="color:#f59e0b;font-weight:700;text-decoration:none;">Sign Up</a>
    <span style="color:#94a3b8;margin-left:8px;">— it's free!</span>
</div>
<?php endif; ?>

<!-- PRODUCTS -->
<section class="products-section" id="products">
    <div>
        <h2 class="section-title">Our <span>Car Collection</span></h2>
        <p class="section-sub">Click any car to add it to your cart. Login required to purchase.</p>
    </div>

    <div class="products-grid">
        <?php
        while ($p = mysqli_fetch_assoc($products)):
            $price_fmt = '₹' . number_format($p['price']);
        ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <div class="card-body">
                <div class="card-brand"><?= htmlspecialchars($p['brand']) ?></div>
                <div class="card-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="card-desc"><?= htmlspecialchars($p['description']) ?></div>
                <div class="card-footer">
                    <span class="card-price"><?= $price_fmt ?></span>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="cart.php?action=add&id=<?= $p['id'] ?>" class="btn btn-primary">Add to Cart</a>
                    <?php else: ?>
                        <a href="login.html" class="btn btn-secondary" title="Login to add to cart">🔒 Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- GAME PROMO BANNER -->
<section style="background:linear-gradient(135deg,#1a1a2e,#0d1117);border-top:1px solid #1e293b;border-bottom:1px solid #1e293b;padding:50px 30px;text-align:center;">
    <h2 style="color:#fff;font-size:1.8rem;margin-bottom:10px;">🎮 Play <span style="color:#f59e0b;">Test Drive</span> & Win Discount Coupons!</h2>
    <p style="color:#64748b;max-width:550px;margin:0 auto 25px;">Beat the high score in our 2D racing game and unlock exclusive discount codes. The better you race, the bigger your discount!</p>
    <div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap;margin-bottom:25px;">
        <div style="background:#111827;border:1px solid #1e293b;border-radius:10px;padding:16px 24px;text-align:center;">
            <div style="font-size:1.5rem;">🏆</div>
            <div style="color:#f59e0b;font-weight:700;font-size:1rem;">Score 5000+</div>
            <div style="color:#64748b;font-size:0.8rem;">Get 10% Off</div>
        </div>
        <div style="background:#111827;border:1px solid #1e293b;border-radius:10px;padding:16px 24px;text-align:center;">
            <div style="font-size:1.5rem;">🚀</div>
            <div style="color:#f59e0b;font-weight:700;font-size:1rem;">Score 7500+</div>
            <div style="color:#64748b;font-size:0.8rem;">Get 20% Off</div>
        </div>
        <div style="background:#111827;border:1px solid #1e293b;border-radius:10px;padding:16px 24px;text-align:center;">
            <div style="font-size:1.5rem;">⚡</div>
            <div style="color:#f59e0b;font-weight:700;font-size:1rem;">Score 10000+</div>
            <div style="color:#64748b;font-size:0.8rem;">Get 30% Off</div>
        </div>
    </div>
    <a href="game.php" class="btn btn-primary" style="font-size:1rem;padding:14px 36px;">Start Racing Now 🏎</a>
</section>

<footer class="footer">
    &copy; 2026 <span>2D Car Dealer</span> — BCA Final Year Project &nbsp;|&nbsp; Built with ❤️ using PHP, MySQL, HTML, CSS & JavaScript
</footer>

</body>
</html>
