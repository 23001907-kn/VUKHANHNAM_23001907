<?php

class CartItem
{
    public $name;
    public $price;
    public $quantity;
    public function __construct($name, $price, $quantity)
    {
        if ($price <= 0) {
            throw new Exception("Giá sản phẩm phải lớn hơn 0.");
        }
        if ($quantity <= 0) {
            throw new Exception("Số lượng sản phẩm phải lớn hơn 0.");
        }
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getTotal()
    {
        return $this->price * $this->quantity;
    }
}


class ShoppingCart
{
    public $items = [];
    public function addItem($item)
    {
        $this->items[] = $item;
        echo "Đã thêm sản phẩm: {$item->name}<br>";
    }
    public function removeItem($name)
    {
        foreach ($this->items as $index => $item) {
            if ($item->name === $name) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                echo "Đã xóa sản phẩm: $name<br>";
                return;
            }
        }
        echo "Không tìm thấy sản phẩm: $name<br>";
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function displayCart()
    {
        if (empty($this->items)) {
            echo "Giỏ hàng đang trống.<br>";
            return;
        }
        echo "<table border='1' cellpadding='8'>";
        echo "<tr>
                <th>Tên sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
              </tr>";

        foreach ($this->items as $item) {
            echo "<tr>";
            echo "<td>{$item->name}</td>";
            echo "<td>" . number_format($item->price) . " VNĐ</td>";
            echo "<td>{$item->quantity}</td>";
            echo "<td>" . number_format($item->getTotal()) . " VNĐ</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
}

//test
$cart = new ShoppingCart();

// 1. Tạo 10 sản phẩm
$item1 = new CartItem("Laptop", 20000000, 1);
$item2 = new CartItem("Chuột", 500000, 2);
$item3 = new CartItem("Bàn phím", 1000000, 1);
$item4 = new CartItem("Tai nghe", 1500000, 2);
$item5 = new CartItem("Màn hình", 5000000, 1);
$item6 = new CartItem("Webcam", 800000, 1);
$item7 = new CartItem("USB", 200000, 3);
$item8 = new CartItem("Ổ cứng SSD", 1800000, 2);
$item9 = new CartItem("Lót chuột", 150000, 2);
$item10 = new CartItem("Loa", 1200000, 1);


// 2. Thêm sản phẩm
echo "<h2>1. THÊM SẢN PHẨM</h2>";

$cart->addItem($item1);
$cart->addItem($item2);
$cart->addItem($item3);
$cart->addItem($item4);
$cart->addItem($item5);
$cart->addItem($item6);
$cart->addItem($item7);
$cart->addItem($item8);
$cart->addItem($item9);
$cart->addItem($item10);


// 3. Hiển thị giỏ hàng
echo "<h2>2. DANH SÁCH GIỎ HÀNG</h2>";

$cart->displayCart();

// 4. Tính tổng tiền
echo "<h2>3. TỔNG TIỀN</h2>";
echo "Tổng tiền: "
    . number_format($cart->calculateTotal())
    . " VNĐ<br>";

// 5. Tìm và xóa sản phẩm
echo "<h2>4. XÓA SẢN PHẨM</h2>";
echo "Sản phẩm cần xóa: Chuột<br>";
$cart->removeItem("Chuột");

// 6. Thử tìm sản phẩm không tồn tại
echo "<h2>5. KIỂM TRA SẢN PHẨM</h2>";
echo "Tìm sản phẩm: Điện thoại<br>";
$cart->removeItem("Điện thoại");

// 7. Hiển thị danh sách còn lại
echo "<h2>6. DANH SÁCH SAU KHI XÓA</h2>";

$cart->displayCart();

// 8. Tính tổng tiền còn lại
echo "<h2>7. TỔNG TIỀN CÒN LẠI</h2>";
echo "Tổng tiền còn lại: "
    . number_format($cart->calculateTotal())
    . " VNĐ<br>";