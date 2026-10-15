<?php
declare(strict_types=1);

function validateProduct(string $name, string $price, string $quantity): array
{
    $errors = [];
    $name = trim($name);
    $nameLength = function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name);

    if ($name === '') {
        $errors['name'] = 'Tên sản phẩm không được để trống.';
    } elseif ($nameLength > 100) {
        $errors['name'] = 'Tên sản phẩm không được vượt quá 100 ký tự.';
    }

    $priceValue = is_numeric($price) ? (float) $price : null;

    if ($priceValue === null || !is_finite($priceValue) || $priceValue <= 0) {
        $errors['price'] = 'Giá phải là số lớn hơn 0.';
    } elseif ($priceValue > 99999999.99) {
        $errors['price'] = 'Giá không được vượt quá 99.999.999,99.';
    }

    $quantityValue = filter_var($quantity, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 2147483647],
    ]);

    if ($quantityValue === false) {
        $errors['quantity'] = 'Số lượng phải là số nguyên từ 0 đến 2.147.483.647.';
    }

    return $errors;
}