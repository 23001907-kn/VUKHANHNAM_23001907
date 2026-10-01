CREATE DATABASE IF NOT EXISTS shopping_cart;

USE shopping_cart;

DROP TABLE IF EXISTS cart_items;

CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    price DECIMAL(12, 2) NOT NULL CHECK (price > 0),
    quantity INT NOT NULL CHECK (quantity > 0)
);

INSERT INTO cart_items (name, price, quantity)
VALUES
    ('Laptop Dell Inspiron', 15000000, 2),
    ('Chuột Logitech', 450000, 10),
    ('Bàn phím cơ Keychron', 1800000, 6),
    ('Tai nghe Bluetooth Sony', 1200000, 4),
    ('USB 64GB Sandisk', 250000, 8),
    ('Màn hình Samsung 24 inch', 3500000, 3),
    ('Webcam Logitech C920', 1800000, 5),
    ('Ổ cứng SSD Samsung 1TB', 2200000, 7),
    ('Balo Laptop Xiaomi', 650000, 9),
    ('Loa Bluetooth JBL', 2500000, 4);

SELECT *
FROM cart_items;

SELECT *
FROM cart_items
WHERE price > 100000;

SELECT *
FROM cart_items
WHERE quantity > 5;

SELECT *
FROM cart_items
ORDER BY price DESC;

UPDATE cart_items
SET price = 500000
WHERE name = 'Chuột Logitech';

SELECT *
FROM cart_items
WHERE name = 'Chuột Logitech';

UPDATE cart_items
SET quantity = 12
WHERE name = 'Chuột Logitech';

SELECT *
FROM cart_items
WHERE name = 'Chuột Logitech';

DELETE FROM cart_items
WHERE name = 'USB 64GB Sandisk';

SELECT *
FROM cart_items;

SELECT
    id,
    name,
    price,
    quantity,
    price * quantity AS total
FROM cart_items;

SELECT
    SUM(price * quantity) AS total_cart
FROM cart_items;