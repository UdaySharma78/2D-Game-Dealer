<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - 2D Car Dealer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .about-hero { background: linear-gradient(135deg, #0d1117, #1a1a2e); padding: 60px 30px; text-align: center; border-bottom: 1px solid #1e293b; }
        .about-hero h1 { font-size: 2.4rem; font-weight: 800; color: #fff; margin-bottom: 12px; }
        .about-hero h1 span { color: #f59e0b; }
        .about-hero p { color: #94a3b8; font-size: 1rem; max-width: 580px; margin: 0 auto; line-height: 1.7; }
        .about-content { max-width: 1000px; margin: 50px auto; padding: 0 30px; }

        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 50px; }
        @media(max-width: 700px) { .about-grid { grid-template-columns: 1fr; } }

        .about-card { background: #111827; border: 1px solid #1e293b; border-radius: 14px; padding: 28px; }
        .about-card .ac-icon { font-size: 2rem; margin-bottom: 14px; }
        .about-card h3 { color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; }
        .about-card p { color: #64748b; font-size: 0.88rem; line-height: 1.7; }

        .feature-list { list-style: none; margin-top: 12px; }
        .feature-list li { color: #94a3b8; font-size: 0.85rem; padding: 5px 0; display: flex; align-items: flex-start; gap: 8px; }
        .feature-list li::before { content: '✅'; flex-shrink: 0; }

        .brands-section { margin-bottom: 50px; }
        .brands-section h2 { color: #fff; font-size: 1.4rem; font-weight: 700; margin-bottom: 20px; }
        .brands-grid { display: flex; flex-wrap: wrap; gap: 10px; }
        .brand-badge { background: #111827; border: 1px solid #1e293b; border-radius: 8px; padding: 8px 16px; color: #f59e0b; font-size: 0.85rem; font-weight: 600; }

        .tech-section { background: #111827; border: 1px solid #1e293b; border-radius: 14px; padding: 30px; margin-bottom: 50px; }
        .tech-section h2 { color: #fff; font-size: 1.2rem; font-weight: 700; margin-bottom: 18px; }
        .tech-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
        .tech-item { background: #0d1117; border: 1px solid #1e293b; border-radius: 8px; padding: 14px; text-align: center; }
        .tech-item .ti-icon { font-size: 1.5rem; margin-bottom: 6px; }
        .tech-item .ti-name { color: #f59e0b; font-size: 0.82rem; font-weight: 700; }
        .tech-item .ti-desc { color: #475569; font-size: 0.72rem; margin-top: 3px; }

        .cta-box { background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(245,158,11,0.03)); border: 1px solid rgba(245,158,11,0.3); border-radius: 14px; padding: 35px; text-align: center; }
        .cta-box h2 { color: #fff; font-size: 1.4rem; margin-bottom: 10px; }
        .cta-box p { color: #64748b; font-size: 0.9rem; margin-bottom: 22px; }
        .cta-buttons { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
    </style>
</head>
<body>

<!-- NAVBAR (static for about page) -->
<nav class="navbar">
    <a href="index.php" class="brand">2D <span>Car Dealer</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="game.php">🎮 Test Drive</a></li>
        <?php if (isset($_SESSION['user_id'])):
            $uid2 = $_SESSION['user_id'];
            $cr = mysqli_query($conn, "SELECT SUM(quantity) as t FROM cart WHERE user_id=$uid2");
            $cc = mysqli_fetch_assoc($cr);
            $cnt = $cc['t'] ? (int)$cc['t'] : 0;
        ?>
            <li>
                <a href="veiw_cart.php" class="cart-link">
                    🛒 Cart <?php if ($cnt > 0): ?><span class="cart-badge"><?= $cnt ?></span><?php endif; ?>
                </a>
            </li>
            <li><span class="username-display">👤 <?= htmlspecialchars($_SESSION['username']) ?></span></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="veiw_cart.php" class="cart-link">🛒 Cart</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html" class="btn btn-primary" style="padding:8px 16px;font-size:0.85rem;">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Hero -->
<div class="about-hero">
    <h1>About <span>2D Car Dealer</span></h1>
    <p>A gamified e-commerce platform where you explore premium toy cars, race in our Test Drive game, win discount coupons, and shop your favourite supercars — all in one place.</p>
</div>

<div class="about-content">

    <!-- About cards -->
    <div class="about-grid">
        <div class="about-card">
            <div class="ac-icon">🏎</div>
            <h3>What is 2D Car Dealer?</h3>
            <p>2D Car Dealer is a BCA Final Year project — a gamified toy car e-commerce website. Users can browse premium car models, add them to cart, and play a 2D racing game to win discount coupons for their purchase.</p>
            <ul class="feature-list">
                <li>Browse 10+ luxury car brands</li>
                <li>Secure user login & registration</li>
                <li>Add to cart & view cart anytime</li>
                <li>Apply discount coupons at checkout</li>
                <li>Place order with delivery details</li>
            </ul>
        </div>

        <div class="about-card">
            <div class="ac-icon">🎮</div>
            <h3>The Test Drive Game</h3>
            <p>Our unique feature — a 2D top-down racing game built in JavaScript. Race your car, dodge enemy vehicles and the longer you survive the higher you score. Beat thresholds to win real discount codes!</p>
            <ul class="feature-list">
                <li>Score 5000+ → 10% off</li>
                <li>Score 7500+ → 20% off</li>
                <li>Score 10000+ → 30% off</li>
                <li>Speed increases as you survive longer</li>
                <li>3 lives per game — dodge to survive!</li>
            </ul>
        </div>

        <div class="about-card">
            <div class="ac-icon">🛒</div>
            <h3>Shopping Cart System</h3>
            <p>After logging in, users can add any car to their cart, view all cart items, remove products and apply discount coupons. The cart page shows a full order summary with pricing.</p>
            <ul class="feature-list">
                <li>Cart items stored in MySQL database</li>
                <li>Live cart count shown in navbar</li>
                <li>Remove individual items from cart</li>
                <li>Coupon applied shows updated total</li>
                <li>Delivery details form before order</li>
            </ul>
        </div>

        <div class="about-card">
            <div class="ac-icon">🔐</div>
            <h3>User Authentication</h3>
            <p>Complete login and registration system built with PHP and MySQL. Passwords are securely hashed using bcrypt. Sessions are used to keep users logged in as they browse.</p>
            <ul class="feature-list">
                <li>Secure signup with hashed passwords</li>
                <li>Login with email and password</li>
                <li>Session-based authentication</li>
                <li>Auto-redirect after login/logout</li>
                <li>Username shown in navbar after login</li>
            </ul>
        </div>
    </div>

    <!-- Car brands -->
    <div class="brands-section">
        <h2>🚗 Car Brands in Our Collection</h2>
        <div class="brands-grid">
            <?php
            $brands = ['Porsche 911','Dodge challenger','Audi','Bentley','Bugatti','Ferrari','Formula 1','Lamborghini','Ford Mustang','Pagani','Toyota Supra','Monster Truck','Many more+'];
            foreach ($brands as $b) {
                echo "<span class='brand-badge'>$b</span>";
            }
            ?>
        </div>
    </div>

    <!-- Tech stack -->
    <div class="tech-section">
        <h2>🛠 Technologies Used</h2>
        <div class="tech-grid">
            <div class="tech-item"><div class="ti-icon">🌐</div><div class="ti-name">HTML5</div><div class="ti-desc">Page structure</div></div>
            <div class="tech-item"><div class="ti-icon">🎨</div><div class="ti-name">CSS3</div><div class="ti-desc">Styling & layout</div></div>
            <div class="tech-item"><div class="ti-icon">⚡</div><div class="ti-name">JavaScript</div><div class="ti-desc">Game & interactivity</div></div>
            <div class="tech-item"><div class="ti-icon">🐘</div><div class="ti-name">PHP</div><div class="ti-desc">Server-side logic</div></div>
            <div class="tech-item"><div class="ti-icon">🗄</div><div class="ti-name">MySQL</div><div class="ti-desc">Database</div></div>
            <div class="tech-item"><div class="ti-icon">🖥</div><div class="ti-name">XAMPP</div><div class="ti-desc">Local server</div></div>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-box">
        <h2>Ready to Explore? 🚀</h2>
        <p>Browse our luxury car collection or race in the Test Drive game to win discount coupons!</p>
        <div class="cta-buttons">
            <a href="index.php" class="btn btn-primary">🚗 Browse Cars</a>
            <a href="game.php" class="btn btn-secondary">🎮 Play Test Drive</a>
        </div>
    </div>

</div><!-- end about-content -->

<footer class="footer">
    &copy; 2026 <span>2D Car Dealer</span> — BCA Final Year Project &nbsp;|&nbsp; Built with PHP, MySQL, HTML, CSS & JavaScript
</footer>

</body>
</html>
