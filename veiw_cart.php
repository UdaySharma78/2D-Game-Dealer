<?php
require 'config.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=Please+login+to+view+your+cart&type=danger");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items with product details
$cart_items = mysqli_query($conn,
    "SELECT cart.id as cart_id, cart.quantity, products.name, products.brand,
            products.price, products.image
     FROM cart
     JOIN products ON cart.product_id = products.id
     WHERE cart.user_id = $user_id
     ORDER BY cart.added_at DESC"
);

$items = [];
$subtotal = 0;
while ($row = mysqli_fetch_assoc($cart_items)) {
    $items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
}

// Cart count for navbar
$cart_count = count($items);

// Handle coupon
$discount_percent = 0;
$coupon_msg = '';
$coupon_applied = '';

if (isset($_POST['apply_coupon'])) {
    $code = strtoupper(trim($_POST['coupon_code'] ?? ''));
    if (!empty($code)) {
        $code_safe = mysqli_real_escape_string($conn, $code);
        $coup = mysqli_query($conn,
            "SELECT * FROM coupons WHERE code = '$code_safe' AND is_active = 1"
        );
        if ($coup && mysqli_num_rows($coup) > 0) {
            $c = mysqli_fetch_assoc($coup);
            $discount_percent = (int)$c['discount_percent'];
            $coupon_applied   = $code;
            $coupon_msg       = "success";
        } else {
            $coupon_msg = "invalid";
        }
    }
}

// Keep coupon from session if previously applied
if (empty($coupon_applied) && isset($_SESSION['coupon'])) {
    $coupon_applied   = $_SESSION['coupon']['code'];
    $discount_percent = $_SESSION['coupon']['percent'];
}

if (!empty($coupon_applied)) {
    $_SESSION['coupon'] = ['code' => $coupon_applied, 'percent' => $discount_percent];
}

$discount_amount = round($subtotal * $discount_percent / 100);
$total = $subtotal - $discount_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - 2D Car Dealer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-wrapper { max-width: 1100px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 360px; gap: 28px; }
        @media(max-width:900px){ .cart-wrapper{ grid-template-columns:1fr; } }

        /* Cart Table */
        .cart-table { background:#111827; border:1px solid #1e293b; border-radius:14px; overflow:hidden; }
        .cart-table-header { padding:20px 24px; border-bottom:1px solid #1e293b; }
        .cart-table-header h2 { color:#fff; font-size:1.3rem; }
        .cart-table table { width:100%; border-collapse:collapse; }
        .cart-table th { background:#0d1117; color:#64748b; font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.8px; padding:12px 16px; text-align:left; }
        .cart-table td { padding:14px 16px; border-bottom:1px solid #0d1117; vertical-align:middle; }
        .cart-table tr:last-child td { border-bottom:none; }
        .cart-table tr:hover td { background:rgba(245,158,11,0.03); }
        .cart-item-img { width:65px; height:45px; object-fit:cover; border-radius:6px; }
        .cart-item-name { font-weight:600; color:#f1f5f9; font-size:0.9rem; }
        .cart-item-brand { font-size:0.74rem; color:#f59e0b; font-weight:600; margin-top:2px; }
        .cart-price { color:#f59e0b; font-weight:700; font-size:0.95rem; }
        .qty-badge { background:#1e293b; color:#f1f5f9; padding:4px 12px; border-radius:6px; font-weight:600; font-size:0.85rem; }
        .empty-cart { text-align:center; padding:60px 20px; color:#475569; }
        .empty-cart .empty-icon { font-size:3.5rem; margin-bottom:14px; }

        /* Right Panel */
        .right-panel { display:flex; flex-direction:column; gap:20px; }

        .panel-box { background:#111827; border:1px solid #1e293b; border-radius:14px; padding:22px; }
        .panel-box h3 { color:#fff; font-size:1rem; font-weight:700; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #1e293b; }

        /* Coupon */
        .coupon-input-row { display:flex; gap:8px; }
        .coupon-input-row input { flex:1; padding:10px 12px; background:#0d1117; border:1px solid #1e293b; border-radius:8px; color:#f1f5f9; font-size:0.9rem; outline:none; transition:border-color 0.2s; text-transform:uppercase; }
        .coupon-input-row input:focus { border-color:#f59e0b; }
        .coupon-input-row input::placeholder { color:#475569; text-transform:none; }

        /* Order Summary */
        .summary-row { display:flex; justify-content:space-between; align-items:center; padding:7px 0; font-size:0.9rem; }
        .summary-row .label { color:#94a3b8; }
        .summary-row .value { color:#f1f5f9; font-weight:600; }
        .summary-row.discount .value { color:#4ade80; }
        .summary-divider { height:1px; background:#1e293b; margin:10px 0; }
        .summary-total .label { color:#fff; font-weight:700; font-size:1rem; }
        .summary-total .value { color:#f59e0b; font-weight:800; font-size:1.15rem; }

        /* Form fields */
        .form-field { margin-bottom:14px; }
        .form-field label { display:block; color:#94a3b8; font-size:0.8rem; font-weight:600; margin-bottom:5px; }
        .form-field input, .form-field select, .form-field textarea {
            width:100%; padding:9px 12px; background:#0d1117; border:1px solid #1e293b;
            border-radius:8px; color:#f1f5f9; font-size:0.88rem; outline:none; transition:border-color 0.2s;
            font-family:inherit;
        }
        .form-field input:focus, .form-field select:focus, .form-field textarea:focus { border-color:#f59e0b; }
        .form-field select option { background:#111827; }
        .form-field textarea { resize:vertical; min-height:70px; }
        .required-star { color:#f87171; }

        /* Place Order */
        .place-order-btn { width:100%; padding:14px; background:#16a34a; color:#fff; border:none; border-radius:10px; font-size:1rem; font-weight:700; cursor:pointer; transition:all 0.25s; letter-spacing:0.3px; }
        .place-order-btn:hover { background:#15803d; transform:translateY(-2px); box-shadow:0 6px 20px rgba(22,163,74,0.3); }
        .place-order-btn:disabled { background:#1e293b; color:#475569; cursor:not-allowed; transform:none; box-shadow:none; }

        /* Order success overlay */
        .order-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; }
        .order-overlay.show { display:flex; }
        .order-modal { background:#111827; border:2px solid #16a34a; border-radius:18px; padding:40px 36px; max-width:420px; width:90%; text-align:center; animation:popIn 0.3s ease; }
        @keyframes popIn { from{transform:scale(0.8);opacity:0} to{transform:scale(1);opacity:1} }
        .order-modal .success-icon { font-size:3.5rem; margin-bottom:16px; }
        .order-modal h2 { color:#4ade80; font-size:1.5rem; margin-bottom:10px; }
        .order-modal p { color:#94a3b8; font-size:0.9rem; line-height:1.6; margin-bottom:22px; }
        .order-modal .order-ref { background:#0d1117; border:1px solid #1e293b; border-radius:8px; padding:10px 16px; color:#f59e0b; font-weight:700; font-size:0.95rem; margin-bottom:22px; letter-spacing:1px; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="brand">2D <span>Car Dealer</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="game.php">🎮 Test Drive</a></li>
        <li>
            <a href="veiw_cart.php" class="cart-link active">
                🛒 Cart <?php if ($cart_count > 0): ?><span class="cart-badge"><?= $cart_count ?></span><?php endif; ?>
            </a>
        </li>
        <li><span class="username-display">👤 <?= htmlspecialchars($_SESSION['username']) ?></span></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div style="max-width:1100px;margin:30px auto;padding:0 20px;">
    <h1 style="color:#fff;font-size:1.5rem;margin-bottom:6px;">🛒 Your Shopping Cart</h1>
    <p style="color:#64748b;font-size:0.85rem;margin-bottom:24px;">Review your items, apply coupon & place your order</p>

    <?php if (isset($_GET['removed'])): ?>
        <div class="alert alert-success" style="margin-bottom:20px;">✅ Item removed from cart.</div>
    <?php endif; ?>
</div>

<?php if (empty($items)): ?>
<div style="max-width:500px;margin:40px auto;text-align:center;">
    <div class="empty-cart">
        <div class="empty-icon">🛒</div>
        <h2 style="color:#fff;margin-bottom:10px;">Your cart is empty!</h2>
        <p style="color:#64748b;margin-bottom:25px;">Go explore our amazing car collection and add your favorites.</p>
        <a href="index.php" class="btn btn-primary">Browse Cars →</a>
    </div>
</div>

<?php else: ?>

<div class="cart-wrapper">

    <!-- LEFT: Cart Items -->
    <div>
        <div class="cart-table">
            <div class="cart-table-header">
                <h2>Cart Items (<?= $cart_count ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Details</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item):
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['image']) ?>" class="cart-item-img" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                        <td>
                            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="cart-item-brand"><?= htmlspecialchars($item['brand']) ?></div>
                        </td>
                        <td class="cart-price">₹<?= number_format($item['price']) ?></td>
                        <td><span class="qty-badge"><?= $item['quantity'] ?></span></td>
                        <td class="cart-price">₹<?= number_format($item_total) ?></td>
                        <td>
                            <a href="cart.php?action=remove&id=<?= $item['cart_id'] ?>"
                               class="btn btn-danger"
                               style="padding:6px 12px;font-size:0.78rem;"
                               onclick="return confirm('Remove this item?')">
                               🗑 Remove
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Coupon Section -->
        <div class="panel-box" style="margin-top:20px;">
            <h3>🎮 Apply Discount Coupon</h3>
            <p style="color:#64748b;font-size:0.82rem;margin-bottom:14px;">
                Win coupons by playing the <a href="game.php" style="color:#f59e0b;">Test Drive game</a>!
                Score 500+ for 10% off, 1000+ for 20% off, 2000+ for 30% off.
            </p>
            <form method="POST">
                <div class="coupon-input-row">
                    <input type="text" name="coupon_code" placeholder="Enter coupon code (e.g. RACE10)"
                           value="<?= htmlspecialchars($coupon_applied) ?>" maxlength="20">
                    <button type="submit" name="apply_coupon" class="btn btn-primary" style="white-space:nowrap;padding:10px 18px;">Apply</button>
                </div>
                <?php if ($coupon_msg === 'success'): ?>
                    <div class="alert alert-success" style="margin-top:10px;">✅ Coupon <strong><?= $coupon_applied ?></strong> applied! <?= $discount_percent ?>% discount unlocked.</div>
                <?php elseif ($coupon_msg === 'invalid'): ?>
                    <div class="alert alert-danger" style="margin-top:10px;">❌ Invalid coupon code. Please check and try again.</div>
                <?php elseif (!empty($coupon_applied)): ?>
                    <div class="alert alert-success" style="margin-top:10px;">✅ Coupon <strong><?= $coupon_applied ?></strong> applied — <?= $discount_percent ?>% off!</div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">

        <!-- Order Summary -->
        <div class="panel-box">
            <h3>📋 Order Summary</h3>
            <div class="summary-row">
                <span class="label">Subtotal (<?= $cart_count ?> items)</span>
                <span class="value">₹<?= number_format($subtotal) ?></span>
            </div>
            <?php if ($discount_percent > 0): ?>
            <div class="summary-row discount">
                <span class="label">Discount (<?= $discount_percent ?>% off)</span>
                <span class="value">− ₹<?= number_format($discount_amount) ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span class="label">Delivery</span>
                <span class="value" style="color:#4ade80;">FREE 🚚</span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row summary-total">
                <span class="label">Total</span>
                <span class="value">₹<?= number_format($total) ?></span>
            </div>
        </div>

        <!-- Delivery & Payment Form -->
        <div class="panel-box">
            <h3>📦 Delivery & Payment</h3>
            <form id="orderForm">
                <div class="form-field">
                    <label>Full Name <span class="required-star">*</span></label>
                    <input type="text" id="f_name" placeholder="Your full name" required>
                </div>
                <div class="form-field">
                    <label>Mobile Number <span class="required-star">*</span></label>
                    <input type="tel" id="f_phone" placeholder="10-digit mobile number" required maxlength="10" pattern="[0-9]{10}">
                </div>
                <div class="form-field">
                    <label>Email Address <span class="required-star">*</span></label>
                    <input type="email" id="f_email" placeholder="Order confirmation email" required value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
                </div>
                <div class="form-field">
                    <label>Delivery Address <span class="required-star">*</span></label>
                    <textarea id="f_address" placeholder="House No, Street, City, State, PIN" required></textarea>
                </div>
                <div class="form-field">
                    <label>Payment Method <span class="required-star">*</span></label>
                    <select id="f_payment" required>
                        <option value="">-- Select Payment --</option>
                        <option value="cod">💵 Cash on Delivery</option>
                        <option value="upi">📱 UPI Payment</option>
                        <option value="card">💳 Credit / Debit Card</option>
                        <option value="netbanking">🏦 Net Banking</option>
                    </select>
                </div>

                <button type="submit" class="place-order-btn" id="placeOrderBtn" disabled>
                    Place Order 🚀
                </button>
                <p style="color:#475569;font-size:0.75rem;text-align:center;margin-top:8px;">
                    * Fill all fields above to enable Place Order button
                </p>
            </form>
        </div>

        <a href="index.php" class="btn btn-secondary" style="text-align:center;">← Continue Shopping</a>
    </div>
</div>

<!-- ORDER SUCCESS OVERLAY -->
<div class="order-overlay" id="orderOverlay">
    <div class="order-modal">
        <div class="success-icon">🎉</div>
        <h2>Order Placed!</h2>
        <p>Thank you for your order! Your order has been placed successfully.</p>
        <div class="order-ref" id="orderRef">ORDER #CD-00000</div>
        <p style="font-size:0.85rem;">
            📧 All order details have been sent to your registered email address. You'll receive your cars soon!
        </p>
        <br>
        <a href="index.php" class="btn btn-primary" style="width:100%;display:block;text-align:center;"
           onclick="clearCartSession()">Back to Home 🏠</a>
    </div>
</div>

<?php endif; ?>

<footer class="footer">
    &copy; 2026 <span>2D Car Dealer</span> — BCA Final Year Project
</footer>

<script>
// Enable Place Order button only when all fields filled
const fields = ['f_name', 'f_phone', 'f_email', 'f_address', 'f_payment'];
const placeBtn = document.getElementById('placeOrderBtn');

function checkForm() {
    if (!placeBtn) return;
    const allFilled = fields.every(id => {
        const el = document.getElementById(id);
        return el && el.value.trim() !== '';
    });
    placeBtn.disabled = !allFilled;
}

fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', checkForm);
});

// Place Order
document.getElementById('orderForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    // Generate random order ID
    const ref = 'CD-' + Math.floor(100000 + Math.random() * 900000);
    document.getElementById('orderRef').textContent = 'ORDER #' + ref;

    // Show success overlay
    document.getElementById('orderOverlay').classList.add('show');
});

function clearCartSession() {
    // After order placed, optionally clear — handled server-side if needed
}
</script>
</body>
</html>
