-- ============================================
-- 2D Car Dealer - Database Setup
-- Run this SQL in phpMyAdmin or MySQL terminal
-- ============================================

CREATE DATABASE IF NOT EXISTS car_dealer;
USE car_dealer;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(200),
    description TEXT,
    category VARCHAR(50) DEFAULT 'car'
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Coupon codes table
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    discount_percent INT NOT NULL,
    min_score INT DEFAULT 0,
    is_active TINYINT DEFAULT 1
);

-- Insert sample products
INSERT INTO products (name, brand, price, image, description) VALUES
('Porsche 911', 'Porsche', 178, 'Z/porshe.jpg',
 'Legendary sports car with timeless styling and performance.'),

('Dodge challenger', 'Dodge', 182, 'Z/dodge.jpg',
 'American muscle car known for raw power and bold design.'),

('BMW M4', 'BMW', 190, 'Z/bmw.jpg',
 'Sporty coupe with twin-turbo performance and premium engineering.'),

('Toyota Supra', 'Toyota', 162, 'Z/supra.webp',
 'Famous JDM car with turbocharged inline-6 engine.');

('Audi R8', 'Audi', 185, 'Z/audii.jpg',
 'High-performance Audi sports car with aggressive styling and powerful engine.'),

('Bentley GT', 'Bentley', 150, 'Z/bentley.jpg',
 'Luxury grand tourer combining elegance with extreme performance.'),

('Bugatti Chiron', 'Bugatti', 212, 'Z/bugatti.webp',
 'Ultimate hypercar with 1500 HP and unmatched speed.'),

('Ferrari 488', 'Ferrari', 165, 'Z/ferrari.webp',
 'Iconic Italian supercar with twin-turbo V8 and stunning design.'),

('Formula 1 Car', 'Formula 1', 250, 'Z/formula1.webp',
 'Professional F1 race car with extreme aerodynamics and speed.'),

('Mercedes G-Wagon', 'Mercedes', 200, 'Z/gwagon.jpg',
 'Luxury SUV with strong road presence and off-road capability.'),

('Lamborghini Huracan', 'Lamborghini', 145, 'Z/lamborgini.webp',
 'Exotic supercar powered by a naturally aspirated V10 engine.'),

('Monster Truck', 'Custom', 175, 'Z/monster truck.webp',
 'Massive truck with oversized tires built for extreme terrain.'),

('Ford Mustang', 'Ford', 150, 'Z/mustang.webp',
 'Classic American muscle car with powerful performance.'),

('Pagani Huayra', 'Pagani', 155, 'Z/pagani.webp',
 'Luxury handcrafted hypercar with AMG V12 engine.'),

-- Insert coupon codes (earned by beating scores in the game)
INSERT INTO coupons (code, discount_percent, min_score) VALUES
('RACE10', 10, 5000),
('SPEED20', 20, 7500),
('TURBO30', 30, 10000);
