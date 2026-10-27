-- ==========================================
-- BOSNIAN THRIFT SHOP - DATABASE SCHEMA
-- ==========================================
DROP DATABASE IF EXISTS web_project;
CREATE DATABASE web_project;
USE web_project;
-- 1. Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'seller') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categories Table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL
);

-- 3. Items Table
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    rating DECIMAL(3,2) DEFAULT 0.00,
    status ENUM('available', 'sold') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_item_seller FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_item_category FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- 4. Item Images Table (New Entity for Item Photos)
CREATE TABLE item_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL, -- Stores file path or cloud URL (e.g., Cloudinary/AWS S3)
    is_primary BOOLEAN DEFAULT FALSE, -- Flag to check if it's the main thumbnail card image
    CONSTRAINT fk_image_item FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);

-- 5. Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    item_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_buyer FOREIGN KEY (buyer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_order_item FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);

-- 6. Reviews Table
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_rating CHECK (rating >= 1 AND rating <= 5),
    CONSTRAINT fk_review_item FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    CONSTRAINT fk_review_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ==========================================
-- BOSNIAN THRIFT SHOP - SAMPLE SEED DATA
-- ==========================================

-- 1. Insert Users (Mix of admin, seller, and standard user roles)
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Amar', 'Hodžić', 'amar.admin@thrift.ba', 'hashed_pass_123', 'admin'),
('Lejla', 'Kovač', 'lejla.seller@thrift.ba', 'hashed_pass_123', 'seller'),
('Tariq', 'Mehmedović', 'tariq.seller@thrift.ba', 'hashed_pass_123', 'seller'),
('Amina', 'Džinović', 'amina.buyer@gmail.com', 'hashed_pass_123', 'user'),
('Dino', 'Spahić', 'dino.buyer@hotmail.com', 'hashed_pass_123', 'user');

-- 2. Insert Categories
INSERT INTO categories (name, description) VALUES
('Clothing & Apparel', 'Pre-loved and vintage clothes, jackets, jeans, and coats.'),
('Footwear', 'Sneakers, boots, shoes, and sandals in various conditions.'),
('Accessories', 'Bags, wallets, belts, scarves, hats, and jewelry.'),
('Books & Media', 'Old novels, textbooks, vinyl records, CDs, and retro games.'),
('Home Decor & Antiques', 'Vases, traditional Bosnian coffee sets, paintings, and knick-knacks.'),
('Electronics', 'Used small appliances, retro gadgets, cables, and audio gear.'),
('Sports & Hobbies', 'Football jerseys, ski gear, camping equipment, and fitness accessories.');

-- 3. Insert Items (Listed by sellers/admins, linked to categories)
-- Note: category_id 1 = Clothing, 2 = Footwear, 5 = Home Decor, 7 = Sports
INSERT INTO items (seller_id, category_id, name, description, price, rating, status) VALUES
(2, 1, 'Vintage 90s Levi Denim Jacket', 'Classic oversized blue denim jacket, great condition, size L.', 45.00, 4.50, 'available'),
(2, 5, 'Traditional Bosnian Copper Džezva Set', 'Handmade old copper coffee pot with 2 matching fildžani cups. Antique piece.', 60.00, 5.00, 'available'),
(3, 7, 'Retro FK Željezničar Football Jersey', 'Classic 1998 home kit jersey, collector item, size M.', 80.00, 4.80, 'sold'),
(3, 2, 'Nike Air Max 97 (Worn Twice)', 'White/Silver colorway, size 42, minor scuffs on the left sole.', 70.00, 4.00, 'available'),
(2, 1, 'Y2K Oversized Aesthetic Hoodie', 'Black hoodie with grunge graphics, slightly faded for a vintage look.', 25.00, 0.00, 'available');

-- 4. Insert Item Images (Multiple photos for items)
-- Note: item_id 1 = Denim Jacket, 2 = Džezva, 3 = Jersey, 4 = Nike Shoes, 5 = Hoodie
INSERT INTO item_images (item_id, image_url, is_primary) VALUES
(1, 'https://images.unsplash.com/photo-1543076447-215ad9ba6923', TRUE),
(1, 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0', FALSE),
(2, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd', TRUE),
(3, 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2', TRUE),
(4, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff', TRUE),
(5, 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2', TRUE);

-- 5. Insert Orders (Records of purchases)
-- Note: Buyer 4 (Amina) bought item 3 (FK Željezničar Jersey) from seller 3 (Tariq)
INSERT INTO orders (buyer_id, item_id, total_price) VALUES
(4, 3, 80.00);

-- 6. Insert Reviews (Feedback left by users on items)
-- Note: Amina reviews the Jersey she bought; Dino reviews the Džezva
INSERT INTO reviews (item_id, user_id, rating, comment) VALUES
(3, 4, 5, 'Prezadovoljna kupovinom! Dres je u super stanju, baš kao sa slike.'),
(2, 5, 5, 'Prelijep džezva set, stiglo dobro upakovano. Svaka preporuka za prodavača!');