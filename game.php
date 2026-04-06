<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Drive - 2D Car Dealer</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>

<!-- NAVBAR -->
<?php
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $r = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id = $uid");
    $rr = mysqli_fetch_assoc($r);
    $cart_count = $rr['total'] ? (int)$rr['total'] : 0;
}
?>
<nav class="navbar">
    <a href="index.php" class="brand">2D <span>Car Dealer</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="game.php" class="active">🎮 Test Drive</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li>
                <a href="veiw_cart.php" class="cart-link">
                    🛒 Cart <?php if ($cart_count > 0): ?><span class="cart-badge"><?= $cart_count ?></span><?php endif; ?>
                </a>
            </li>
            <li><span class="username-display">👤 <?= htmlspecialchars($_SESSION['username']) ?></span></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html" style="background:#f59e0b;color:#0d1117;padding:8px 16px;border-radius:6px;font-weight:700;font-size:0.85rem;">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="game-page">

    <!-- ── INTRO SCREEN ── -->
    <div id="introScreen">
        <h1>🏎 <span>Test Drive</span> Challenge</h1>
        <p class="intro-sub">
            Race your car, dodge enemies and beat the high score to win exclusive discount coupons!
            Steer with arrow keys ← →. Every crash costs a life. You have 3 lives.
        </p>

        <!-- Reward tiers -->
        <div class="reward-tiers">
            <div class="tier-card">
                <div class="tier-icon">🥉</div>
                <div class="tier-score">Score 5000+</div>
                <div class="tier-reward">Get 10% Discount</div>
            </div>
            <div class="tier-card">
                <div class="tier-icon">🥈</div>
                <div class="tier-score">Score 7500+</div>
                <div class="tier-reward">Get 20% Discount</div>
            </div>
            <div class="tier-card">
                <div class="tier-icon">🥇</div>
                <div class="tier-score">Score 10000+</div>
                <div class="tier-reward">Get 30% Discount</div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls-info">
            <div class="ctrl-item"><strong>← →</strong> Arrow Keys to Steer</div>
            <div class="ctrl-item"><strong>3</strong> Lives per game</div>
            <div class="ctrl-item"><strong>Speed</strong> increases over time</div>
            <div class="ctrl-item"><strong>Dodge</strong> all enemy cars</div>
        </div>

        <button id="startBtn" onclick="startGame()">🚀 Start Game</button>
    </div>

    <!-- ── GAME AREA ── -->
    <div id="gameArea">
        <!-- HUD -->
        <div id="hud">
            <div class="hud-box">
                <div class="hud-label">Score</div>
                <div class="hud-value" id="scoreDisplay">0</div>
            </div>
            <div class="hud-box">
                <div class="hud-label">High Score</div>
                <div class="hud-value" id="highScoreDisplay">0</div>
            </div>
            <div class="hud-box">
                <div class="hud-label">Speed</div>
                <div class="hud-value" id="speedDisplay">1x</div>
            </div>
            <div class="hud-box hud-lives">
                <div class="hud-label">Lives</div>
                <div class="hud-value" id="livesDisplay">♥♥♥</div>
            </div>
        </div>

        <canvas id="gameCanvas" width="400" height="550"></canvas>

        <!-- Mobile controls -->
        <div id="mobileControls">
            <button class="mobile-btn" id="leftBtn">◀</button>
            <button class="mobile-btn" id="rightBtn">▶</button>
        </div>
    </div>

    <!-- ── GAME OVER SCREEN ── -->
    <div id="gameOverScreen">
        <div class="go-icon" id="goIcon">💥</div>
        <h2 id="goTitle">Game Over!</h2>
        <p class="go-sub" id="goSub">Better luck next time. Keep racing to unlock discounts!</p>

        <div class="score-display">
            <div class="score-box">
                <div class="s-label">Your Score</div>
                <div class="s-value" id="finalScore">0</div>
            </div>
            <div class="score-box">
                <div class="s-label">High Score</div>
                <div class="s-value" id="finalHighScore">0</div>
            </div>
        </div>

        <!-- Coupon won -->
        <div class="coupon-won" id="couponWon">
            <div class="cw-title">🎉 Congratulations! You unlocked a discount coupon!</div>
            <div class="cw-code" id="couponCode" onclick="copyCoupon()" title="Click to copy">RACE10</div>
            <div class="cw-desc">Click the code to copy it. Apply it in your cart to get your discount!</div>
        </div>

        <!-- No coupon -->
        <div class="no-coupon" id="noCoupon">
            😔 You didn't reach the minimum score of 5000. Keep trying!<br>Score 5000+ to get 10% off!
        </div>

        <div class="go-buttons">
            <button id="tryAgainBtn" onclick="restartGame()">🔄 Try Again</button>
            <a href="index.php" id="buyNowBtn">🛒 Buy Now</a>
        </div>
    </div>

</div><!-- end game-page -->

<footer class="footer">
    &copy; 2026 <span>2D Car Dealer</span> — BCA Final Year Project
</footer>

<script src="game.js"></script>
</body>
</html>
