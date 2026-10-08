<?php

require_once "model/product.php";
require_once "view/header.php";
$products = getAllProducts();

?>

<a href="product_add.php">Thêm sản phẩm</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Chức năng</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product["id"] ?></td>
            <td><?= htmlspecialchars($product["name"]) ?></td>
            <td><?= number_format($product["price"], 2) ?></td>
            <td><?= $product["quantity"] ?></td>
            <td>
                <a href="product_edit.php?id=<?= $product["id"] ?>">
                    Sửa
                </a>
                |
                <a
                    href="product_delete.php?id=<?= $product["id"] ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                >
                    Xóa
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once "view/footer.php"; ?>