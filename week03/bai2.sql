CREATE DATABASE IF NOT EXISTS movie_ticket;

USE movie_ticket;

DROP TABLE IF EXISTS movies;

CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL UNIQUE,
    price DECIMAL(12, 2) NOT NULL CHECK (price > 0),
    total_seats INT NOT NULL CHECK (total_seats > 0),
    available_seats INT NOT NULL CHECK (
        available_seats >= 0
        AND available_seats <= total_seats
    )
);

INSERT INTO movies (title, price, total_seats, available_seats)
VALUES
    ('Avengers: Endgame', 120000, 120, 40),
    ('Spider-Man: No Way Home', 110000, 100, 30),
    ('Doraemon: Nobita va Vung Dat Ly Tuong', 90000, 150, 80),
    ('The Batman', 130000, 100, 25),
    ('Avatar: The Way of Water', 150000, 200, 120),
    ('Interstellar', 100000, 80, 20),
    ('Inception', 115000, 120, 35),
    ('The Dark Knight', 125000, 100, 15),
    ('Kung Fu Panda 4', 85000, 150, 95),
    ('Oppenheimer', 140000, 100, 45);

SELECT *
FROM movies;

SELECT *
FROM movies
WHERE price > 100000;

SELECT *
FROM movies
WHERE available_seats > 50;

SELECT *
FROM movies
ORDER BY price DESC;

UPDATE movies
SET available_seats = 100
WHERE title = 'Avatar: The Way of Water';

SELECT *
FROM movies
WHERE title = 'Avatar: The Way of Water';

DELETE FROM movies
WHERE title = 'Interstellar';

SELECT *
FROM movies;

SELECT
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets
FROM movies;

SELECT
    title,
    price,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets,
    (total_seats - available_seats) * price AS revenue
FROM movies;

SELECT
    SUM((total_seats - available_seats) * price) AS total_revenue
FROM movies;

SELECT
    MAX(total_seats - available_seats) AS max_sold_tickets
FROM movies;

SELECT
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets
FROM movies
WHERE (total_seats - available_seats) = (
    SELECT MAX(total_seats - available_seats)
    FROM movies
);