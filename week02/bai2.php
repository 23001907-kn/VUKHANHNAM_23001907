<?php

class Movie
{
    public int $id;
    public string $title;
    public float $price;
    public int $totalSeats;
    public int $availableSeats;

    public function __construct(
        int $id,
        string $title,
        float $price,
        int $totalSeats
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }

    public function bookTicket(int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }
        if ($quantity > $this->availableSeats) {
            return false;
        }
        $this->availableSeats -= $quantity;
        return true;
    }

    public function cancelTicket(int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }
        if ($quantity > $this->getSoldSeats()) {
            return false;
        }
        $this->availableSeats += $quantity;
        return true;
    }

    public function getSoldSeats(): int
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue(): float
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo(): void
    {
        echo "<tr>";
        echo "<td>{$this->id}</td>";
        echo "<td>{$this->title}</td>";
        echo "<td>" . number_format($this->price) . " VNĐ</td>";
        echo "<td>{$this->totalSeats}</td>";
        echo "<td>{$this->availableSeats}</td>";
        echo "<td>{$this->getSoldSeats()}</td>";
        echo "<td>" . number_format($this->getRevenue()) . " VNĐ</td>";
        echo "</tr>";
    }
}

function findMovieById(array $movies, int $id): ?Movie
{
    foreach ($movies as $movie) {
        if ($movie->id === $id) {
            return $movie;
        }
    }

    return null;
}

function getTotalRevenue(array $movies): float
{
    $totalRevenue = 0;
    foreach ($movies as $movie) {
        $totalRevenue += $movie->getRevenue();
    }

    return $totalRevenue;
}

function getBestSellingMovie(array $movies): ?Movie
{
    if (empty($movies)) {
        return null;
    }
    $bestSellingMovie = $movies[0];
    foreach ($movies as $movie) {
        if ($movie->getSoldSeats() > $bestSellingMovie->getSoldSeats()) {
            $bestSellingMovie = $movie;
        }
    }
    return $bestSellingMovie;
}

//test
echo "<h1>QUẢN LÝ VÉ XEM PHIM</h1>";

// 1. TẠO DANH SÁCH PHIM
$movies = [
    new Movie(1, "Avengers", 100000, 100),
    new Movie(2, "Avatar", 120000, 80),
    new Movie(3, "Batman", 90000, 120)
];

// 2. ĐẶT VÉ AVENGERS
$avengers = findMovieById($movies, 1);
if ($avengers !== null) {
    if ($avengers->bookTicket(30)) {
        echo "<p>Đã đặt 30 vé Avengers.</p>";
    }
}

// 3. ĐẶT VÉ AVATAR
$avatar = findMovieById($movies, 2);

if ($avatar !== null) {
    if ($avatar->bookTicket(40)) {
        echo "<p>Đã đặt 40 vé Avatar.</p>";
    }
}

// 4. HỦY VÉ AVENGERS
if ($avengers !== null) {
    if ($avengers->cancelTicket(10)) {
        echo "<p>Đã hủy 10 vé Avengers.</p>";
    }
}

// 5. HIỂN THỊ TẤT CẢ PHIM
echo "<h2>THÔNG TIN CÁC BỘ PHIM</h2>";

echo "<table border='1' cellpadding='8' cellspacing='0'>";

echo "<tr>";
echo "<th>ID</th>";
echo "<th>Tên phim</th>";
echo "<th>Giá vé</th>";
echo "<th>Tổng ghế</th>";
echo "<th>Ghế còn lại</th>";
echo "<th>Vé đã bán</th>";
echo "<th>Doanh thu</th>";
echo "</tr>";

foreach ($movies as $movie) {
    $movie->displayInfo();
}

echo "</table>";

// 6. TỔNG DOANH THU
echo "<h2>TỔNG DOANH THU</h2>";

echo number_format(
    getTotalRevenue($movies)
);
echo " VNĐ";

// 7. PHIM BÁN NHIỀU NHẤT
echo "<h2>PHIM BÁN NHIỀU NHẤT</h2>";

$bestSellingMovie = getBestSellingMovie($movies);
if ($bestSellingMovie !== null) {
    echo "Tên phim: {$bestSellingMovie->title}<br>";
    echo "Số vé bán: "
        . $bestSellingMovie->getSoldSeats()
        . "<br>";
    echo "Doanh thu: "
        . number_format($bestSellingMovie->getRevenue())
        . " VNĐ";
}
?>

