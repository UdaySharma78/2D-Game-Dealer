# 2D Car Dealer - Setup Instructions
## BCA Final Year Project

---

## How to Run This Project

### Step 1 — Install XAMPP
- Download XAMPP from: https://www.apachefriends.org/
- Install and open XAMPP Control Panel
- Click **Start** next to **Apache** and **MySQL**

### Step 2 — Copy Project Files
- Open File Explorer and go to: `C:\xampp\htdocs\`
- Copy the entire **fweb06** folder there
- Final path should be: `C:\xampp\htdocs\fweb06\`

### Step 3 — Setup Database
- Open your browser and go to: `http://localhost/phpmyadmin`
- Click **New** on the left sidebar
- Create a database named: `car_dealer`
- Click on `car_dealer` database
- Click **Import** tab at the top
- Click **Choose File** and select: `fweb06/database.sql`
- Click **Go** / **Import**
- ✅ All tables and products will be created automatically!

### Step 4 — Open the Website
- Go to: `http://localhost/fweb06/`
- The homepage should load with all cars!

---

## Files in This Project

| File | Purpose |
|------|---------|
| `index.php` | Home page — shows all car products |
| `login.html` | Login page |
| `login.php` | Login handler (checks DB) |
| `signup.html` | Registration page |
| `signup.php` | Signup handler (adds to DB) |
| `logout.php` | Clears session, logs user out |
| `cart.php` | Add/Remove from cart |
| `veiw_cart.php` | Cart page with coupon & order form |
| `game.php` | Test Drive game page |
| `game.js` | Complete game logic (JavaScript) |
| `game.css` | Game page styles |
| `style.css` | Main website styles |
| `auth.css` | Login/Signup page styles |
| `about.php` | About page |
| `config.php` | Database connection |
| `database.sql` | Run this to set up all tables |

---

## How the Website Works

1. **Home Page** — User sees all cars (no login needed to browse)
2. **Login Banner** — Shown if user is not logged in
3. **Sign Up / Login** — User creates account or logs in
4. **After Login** — Username shown in navbar, can add to cart
5. **Add to Cart** — Click "Add to Cart" on any product
6. **View Cart** — See all added items, remove if needed
7. **Apply Coupon** — Enter coupon code won from game
8. **Test Drive Game** — Play to win coupon codes:
   - Score 500+ → **RACE10** (10% off)
   - Score 1000+ → **SPEED20** (20% off)
   - Score 2000+ → **TURBO30** (30% off)
9. **Place Order** — Fill delivery details, click Place Order
10. **Logout** — Click Logout in navbar

---

## Database Details

- **Database name:** `car_dealer`
- **Tables:** `users`, `products`, `cart`, `coupons`
- **Default host:** localhost
- **Username:** root
- **Password:** (empty — default XAMPP)

If your MySQL has a different password, edit `config.php`:
```php
$pass = "your_password_here";
```

---

## Coupon Codes

| Code | Discount | Condition |
|------|----------|-----------|
| RACE10 | 10% off | Score 500+ in game |
| SPEED20 | 20% off | Score 1000+ in game |
| TURBO30 | 30% off | Score 2000+ in game |

---

*Made with ❤️ using PHP, MySQL, HTML, CSS & JavaScript*
