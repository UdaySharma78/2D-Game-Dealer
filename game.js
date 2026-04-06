// ============================================================
// 2D Car Dealer - Test Drive Racing Game
// Simple, beginner-friendly JavaScript game
// ============================================================

// ── Canvas setup ──────────────────────────────────────────
const canvas  = document.getElementById('gameCanvas');
const ctx     = canvas.getContext('2d');
const W = canvas.width;   // 400
const H = canvas.height;  // 550

// ── Game state variables ───────────────────────────────────
let score       = 0;
let highScore   = parseInt(localStorage.getItem('carDealerHighScore') || '0');
let lives       = 3;
let gameRunning = false;
let gameLoop    = null;
let frameCount  = 0;
let gameSpeed   = 3;       // Road scroll speed
let enemySpeed  = 3;

// ── Road / track ──────────────────────────────────────────
const ROAD_LEFT   = 80;
const ROAD_RIGHT  = W - 80;
const ROAD_WIDTH  = ROAD_RIGHT - ROAD_LEFT;

let roadY = 0;   // for scrolling

// ── Player car ────────────────────────────────────────────
const player = {
    x: W / 2 - 22,   // center of road
    y: H - 120,
    w: 44,
    h: 75,
    speed: 5,
    color: '#3B82F6',
    moving: { left: false, right: false }
};

// ── Enemies array ──────────────────────────────────────────
let enemies = [];

const ENEMY_COLORS = ['#EF4444', '#F97316', '#8B5CF6', '#10B981', '#EC4899'];

function spawnEnemy() {
    // Random lane position within road
    const margin = 10;
    const ex = ROAD_LEFT + margin + Math.random() * (ROAD_WIDTH - player.w - margin * 2);
    enemies.push({
        x: ex,
        y: -90,
        w: 44,
        h: 70,
        color: ENEMY_COLORS[Math.floor(Math.random() * ENEMY_COLORS.length)],
        speed: enemySpeed + Math.random() * 1.5
    });
}

// ── Keyboard input ────────────────────────────────────────
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft')  player.moving.left  = true;
    if (e.key === 'ArrowRight') player.moving.right = true;
});

document.addEventListener('keyup', function(e) {
    if (e.key === 'ArrowLeft')  player.moving.left  = false;
    if (e.key === 'ArrowRight') player.moving.right = false;
});

// ── Mobile buttons ────────────────────────────────────────
const leftBtn  = document.getElementById('leftBtn');
const rightBtn = document.getElementById('rightBtn');

if (leftBtn) {
    leftBtn.addEventListener('touchstart',  () => player.moving.left  = true);
    leftBtn.addEventListener('touchend',    () => player.moving.left  = false);
    leftBtn.addEventListener('mousedown',   () => player.moving.left  = true);
    leftBtn.addEventListener('mouseup',     () => player.moving.left  = false);
}
if (rightBtn) {
    rightBtn.addEventListener('touchstart', () => player.moving.right = true);
    rightBtn.addEventListener('touchend',   () => player.moving.right = false);
    rightBtn.addEventListener('mousedown',  () => player.moving.right = true);
    rightBtn.addEventListener('mouseup',    () => player.moving.right = false);
}

// ── Drawing helpers ───────────────────────────────────────

function drawRoad() {
    // Dark background
    ctx.fillStyle = '#1a1a2e';
    ctx.fillRect(0, 0, W, H);

    // Road surface
    ctx.fillStyle = '#374151';
    ctx.fillRect(ROAD_LEFT, 0, ROAD_WIDTH, H);

    // Road edges (white lines)
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(ROAD_LEFT,       0, 5, H);
    ctx.fillRect(ROAD_RIGHT - 5,  0, 5, H);

    // Center dashed line (scrolling)
    ctx.fillStyle = '#F59E0B';
    ctx.setLineDash([40, 25]);
    ctx.strokeStyle = '#F59E0B';
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.moveTo(W / 2, roadY % 65 - 65);
    ctx.lineTo(W / 2, H + 65);
    ctx.stroke();
    ctx.setLineDash([]);

    // Grass on sides
    ctx.fillStyle = '#14532d';
    ctx.fillRect(0, 0, ROAD_LEFT, H);
    ctx.fillRect(ROAD_RIGHT, 0, W - ROAD_RIGHT, H);

    // Trees / decorations
    drawTrees();
}

// Simple decorative trees on the side
let treeY = 0;
function drawTrees() {
    const positions = [20, 350, 370, 30];
    for (let i = 0; i < 4; i++) {
        const tx = i < 2 ? positions[i] : positions[i];
        const ty = ((treeY + i * 160) % (H + 60)) - 30;

        // Trunk
        ctx.fillStyle = '#92400e';
        ctx.fillRect(tx - 5, ty + 20, 10, 20);

        // Leaves
        ctx.fillStyle = '#166534';
        ctx.beginPath();
        ctx.arc(tx, ty + 10, 16, 0, Math.PI * 2);
        ctx.fill();
    }
}

function drawCar(x, y, w, h, bodyColor, isPlayer) {
    // Car body
    ctx.fillStyle = bodyColor;
    ctx.beginPath();
    ctx.roundRect(x, y, w, h, 6);
    ctx.fill();

    // Windshield
    ctx.fillStyle = isPlayer ? 'rgba(147,197,253,0.8)' : 'rgba(254,202,202,0.7)';
    if (isPlayer) {
        ctx.fillRect(x + 5, y + 8, w - 10, 22);
    } else {
        ctx.fillRect(x + 5, y + h - 30, w - 10, 22);
    }

    // Wheels
    ctx.fillStyle = '#111';
    // Front wheels
    ctx.fillRect(x - 5, y + 8, 8, 14);
    ctx.fillRect(x + w - 3, y + 8, 8, 14);
    // Rear wheels
    ctx.fillRect(x - 5, y + h - 22, 8, 14);
    ctx.fillRect(x + w - 3, y + h - 22, 8, 14);

    // Wheel rims
    ctx.fillStyle = '#888';
    ctx.fillRect(x - 4, y + 10, 6, 10);
    ctx.fillRect(x + w - 2, y + 10, 6, 10);
    ctx.fillRect(x - 4, y + h - 20, 6, 10);
    ctx.fillRect(x + w - 2, y + h - 20, 6, 10);

    // Headlights / taillights
    if (isPlayer) {
        ctx.fillStyle = '#FCD34D';
        ctx.fillRect(x + 4, y, 8, 5);
        ctx.fillRect(x + w - 12, y, 8, 5);
    } else {
        ctx.fillStyle = '#EF4444';
        ctx.fillRect(x + 4, y + h - 5, 8, 5);
        ctx.fillRect(x + w - 12, y + h - 5, 8, 5);
    }
}

function drawHUD() {
    // Already shown in HTML HUD boxes — just update them
    document.getElementById('scoreDisplay').textContent     = score;
    document.getElementById('highScoreDisplay').textContent = highScore;
    document.getElementById('speedDisplay').textContent     = gameSpeed.toFixed(1) + 'x';

    // Lives hearts
    const hearts = ['♥♥♥', '♥♥', '♥', ''][3 - lives] || '';
    document.getElementById('livesDisplay').textContent = '♥'.repeat(lives);
}

// ── Collision detection ────────────────────────────────────
function isColliding(a, b) {
    // Add a little margin to be fair
    const margin = 6;
    return (
        a.x + margin < b.x + b.w - margin &&
        a.x + a.w - margin > b.x + margin &&
        a.y + margin < b.y + b.h - margin &&
        a.y + a.h - margin > b.y + margin
    );
}

// ── Flash effect on crash ─────────────────────────────────
let flashTimer = 0;

function flashScreen() {
    flashTimer = 8;  // frames to flash
}

// ── Main game loop ─────────────────────────────────────────
function gameFrame() {
    if (!gameRunning) return;

    frameCount++;

    // Scroll road
    roadY += gameSpeed;
    treeY += gameSpeed;

    // Increase difficulty every 300 frames
    if (frameCount % 300 === 0) {
        gameSpeed   = Math.min(gameSpeed + 0.3, 10);
        enemySpeed  = Math.min(enemySpeed + 0.3, 9);
    }

    // Increase score every frame
    score += 1;
    if (score > highScore) {
        highScore = score;
        localStorage.setItem('carDealerHighScore', highScore);
    }

    // Spawn enemies every ~90 frames (less at start)
    const spawnRate = Math.max(55, 100 - frameCount / 60);
    if (frameCount % Math.floor(spawnRate) === 0) {
        spawnEnemy();
    }

    // ── Draw everything ──
    drawRoad();

    // Draw enemies
    for (let i = enemies.length - 1; i >= 0; i--) {
        const e = enemies[i];
        e.y += e.speed;
        drawCar(e.x, e.y, e.w, e.h, e.color, false);

        // Remove off-screen enemies
        if (e.y > H + 100) {
            enemies.splice(i, 1);
            continue;
        }

        // Check collision with player
        if (isColliding(player, e)) {
            enemies.splice(i, 1);
            lives--;
            flashScreen();

            if (lives <= 0) {
                endGame();
                return;
            }
        }
    }

    // Move player
    if (player.moving.left && player.x > ROAD_LEFT + 5) {
        player.x -= player.speed;
    }
    if (player.moving.right && player.x + player.w < ROAD_RIGHT - 5) {
        player.x += player.speed;
    }

    // Draw player
    drawCar(player.x, player.y, player.w, player.h, player.color, true);

    // Flash overlay on crash
    if (flashTimer > 0) {
        ctx.fillStyle = `rgba(239,68,68,${flashTimer / 8 * 0.5})`;
        ctx.fillRect(0, 0, W, H);
        flashTimer--;
    }

    // Draw score on canvas too
    ctx.fillStyle = 'rgba(0,0,0,0.5)';
    ctx.fillRect(0, 0, W, 0);  // minimal

    drawHUD();

    gameLoop = requestAnimationFrame(gameFrame);
}

// ── Start / Restart ────────────────────────────────────────
function startGame() {
    // Hide intro, show game area
    document.getElementById('introScreen').style.display    = 'none';
    document.getElementById('gameOverScreen').style.display = 'none';
    document.getElementById('gameOverScreen').style.flexDirection = 'none';
    document.getElementById('gameArea').style.display       = 'flex';

    resetGame();
    gameRunning = true;
    requestAnimationFrame(gameFrame);
}

function resetGame() {
    score       = 0;
    lives       = 3;
    frameCount  = 0;
    gameSpeed   = 3;
    enemySpeed  = 3;
    enemies     = [];
    flashTimer  = 0;
    roadY       = 0;
    treeY       = 0;
    player.x    = W / 2 - player.w / 2;
    player.moving.left  = false;
    player.moving.right = false;
}

function restartGame() {
    // Hide game over, show game area
    document.getElementById('gameOverScreen').style.display = 'none';
    document.getElementById('gameArea').style.display       = 'flex';

    if (gameLoop) cancelAnimationFrame(gameLoop);
    resetGame();
    gameRunning = true;
    requestAnimationFrame(gameFrame);
}

// ── End game ────────────────────────────────────────────────
function endGame() {
    gameRunning = false;
    if (gameLoop) cancelAnimationFrame(gameLoop);

    // Hide game area, show game over
    document.getElementById('gameArea').style.display         = 'none';
    const goScreen = document.getElementById('gameOverScreen');
    goScreen.style.display = 'flex';

    // Fill in scores
    document.getElementById('finalScore').textContent     = score;
    document.getElementById('finalHighScore').textContent = highScore;

    // Determine coupon
    const couponWon  = document.getElementById('couponWon');
    const noCoupon   = document.getElementById('noCoupon');
    const couponCode = document.getElementById('couponCode');

    couponWon.classList.remove('show');
    noCoupon.classList.remove('show');

    let won = false;
    let code = '';
    let percent = 0;

    if (score >= 10000) {
        code = 'TURBO30'; percent = 30; won = true;
    } else if (score >= 7500) {
        code = 'SPEED20'; percent = 20; won = true;
    } else if (score >= 5000) {
        code = 'RACE10';  percent = 10; won = true;
    }

    if (won) {
        couponCode.textContent = code;
        couponWon.querySelector('.cw-title').textContent =
            `🎉 You unlocked ${percent}% discount!`;
        couponWon.querySelector('.cw-desc').textContent =
            `Use code "${code}" at checkout in your cart!`;
        couponWon.classList.add('show');

        document.getElementById('goIcon').textContent  = '🏆';
        document.getElementById('goTitle').textContent = 'Amazing Race!';
        document.getElementById('goSub').textContent   = `You scored ${score} points and won a coupon!`;
    } else {
        noCoupon.classList.add('show');
        document.getElementById('goIcon').textContent  = '💥';
        document.getElementById('goTitle').textContent = 'Game Over!';
        document.getElementById('goSub').textContent   = `You scored ${score} points. Score 500+ to win a coupon!`;
    }
}

// ── Copy coupon code ───────────────────────────────────────
function copyCoupon() {
    const code = document.getElementById('couponCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const el = document.getElementById('couponCode');
        const orig = el.textContent;
        el.textContent = '✅ Copied!';
        el.style.color = '#4ade80';
        setTimeout(() => {
            el.textContent = orig;
            el.style.color = '';
        }, 1800);
    }).catch(() => {
        // Fallback for older browsers
        alert('Your coupon code: ' + code);
    });
}

// ── Update high score display on load ─────────────────────
document.getElementById('highScoreDisplay').textContent = highScore;
