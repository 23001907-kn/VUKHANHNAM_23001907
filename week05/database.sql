CREATE DATABASE IF NOT EXISTS shopping_cart
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO products (id, name, price, quantity) VALUES
    (1,  'Bàn phím cơ không dây',      990000.00,   14),
    (2,  'Chuột gaming RGB',           450000.00,   22),
    (3,  'Tai nghe chụp tai',          1190000.00,  9),
    (4,  'Màn hình 27 inch 2K',        4590000.00,  6),
    (5,  'Webcam 2K có micro',         790000.00,   12),
    (6,  'Laptop lập trình 16GB RAM',  18990000.00, 5),
    (7,  'Ổ cứng SSD NVMe 512GB',      1290000.00,  17),
    (8,  'USB 3.0 128GB',              290000.00,   35),
    (9,  'Loa máy tính 2.0',           590000.00,   11),
    (10, 'Đế tản nhiệt laptop',        350000.00,   19),
    (11, 'Cáp USB-C sạc nhanh 1m',     150000.00,   40),
    (12, 'Pin sạc dự phòng 10000mAh',  520000.00,   16),
    (13, 'Micro livestream',           1450000.00,  7),
    (14, 'Bàn làm việc chỉnh độ cao',  5290000.00,  3),
    (15, 'Router Wi-Fi 6',             1390000.00,  10);
