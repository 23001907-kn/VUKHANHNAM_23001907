CREATE DATABASE IF NOT EXISTS shopping_cart
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,
    price    DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, quantity) VALUES
('Vở kẻ ngang 200 trang',       18000.00,  200),
('Bút bi Thiên Long (hộp 20)',  60000.00,   80),
('Bút chì gỗ 2B (hộp 12)',      35000.00,   90),
('Tẩy trắng không bụi',          7000.00,  150),
('Thước kẻ nhựa 30cm',          12000.00,  120),
('Cặp chống gai size L',       385000.00,   25),
('Máy tính Casio fx-580VN X',  690000.00,   30),
('Bình giữ nhiệt 500ml',       210000.00,   45),
('Đèn bàn LED chống cận',      275000.00,   18),
('Sổ tay bìa da A5',            95000.00,   60);