<?php

require_once "model/product.php";

$id = $_GET["id"] ?? 0;

$product = getProductById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    if ($name === "") {
        $error = "Tên sản phẩm không được rỗng.";
    } elseif ($price <= 0) {
        $error = "Giá sản phẩm phải lớn hơn 0.";
    } elseif ($quantity < 0) {
        $error = "Số lượng phải lớn hơn hoặc bằng 0.";
    } else {

        updateProduct($id, $name, $price, $quantity);

        header("Location: product_list.php");
        exit;
    }

    $product["name"] = $name;
    $product["price"] = $price;
    $product["quantity"] = $quantity;
}

require_once "view/header.php";

?>

<h2>Sửa sản phẩm</h2>

<?php if ($error): ?>

    <p style="color: red;">
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<form method="POST">

    <label>Tên sản phẩm:</label>
    <input
        type="text"
        name="name"
        value="<?= htmlspecialchars($product["name"]) ?>"
    >

    <br><br>

    <label>Giá:</label>
    <input
        type="number"
        name="price"
        step="0.01"
        value="<?= htmlspecialchars($product["price"]) ?>"
    >

    <br><br>

    <label>Số lượng:</label>
    <input
        type="number"
        name="quantity"
        min="0"
        value="<?= htmlspecialchars($product["quantity"]) ?>"
    >

    <br><br>

    <button type="submit">Cập nhật</button>

    <a href="product_list.php">Quay lại</a>

</form>

<?php require_once "view/footer.php"; ?>