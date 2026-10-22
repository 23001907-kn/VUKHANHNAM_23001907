<?php
$prices = [5000000, 2500000, 350000, 7200000, 15000000, 500000, 1200000, 800000, 1800000, 950000];

function calculateTotal($prices)
{
    $total = 0;
    foreach ($prices as $price) {
        $total += $price;
    }
    return $total;
}

function findMaxPrice($prices)
{
    $maxPrice = null;
    foreach ($prices as $price) {
        if ($maxPrice === null || $price > $maxPrice) {
            $maxPrice = $price;
        }
    }
    return $maxPrice;
}

$total = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

echo "Danh sách giá sản phẩm: ";
$separator = "";
foreach ($prices as $price) {
    echo $separator . $price;
    $separator = ", ";
}
echo "<br>";
echo "Tổng giá trị sản phẩm: " . $total . "<br>";
echo "Giá sản phẩm cao nhất: " . ($maxPrice ?? "Không có sản phẩm") . "<br>";
