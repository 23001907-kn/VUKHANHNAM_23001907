<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    header('Allow: GET, POST, PUT, DELETE');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
}

$id = null;
if ($method === 'PUT' || $method === 'DELETE') {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
    if ($id === false || $id === null) {
        respond(400, ['success' => false, 'message' => 'Mã sản phẩm phải là số nguyên dương hợp lệ.']);
    }
}

$input = null;
if ($method === 'POST' || $method === 'PUT') {
    try {
        $input = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        respond(400, ['success' => false, 'message' => 'Dữ liệu JSON không hợp lệ.']);
    }
    if (!is_object($input) || !isset($input->name) || !is_string($input->name)) {
        respond(422, ['success' => false, 'message' => 'Tên sản phẩm phải là chuỗi từ 1 đến 100 ký tự.']);
    }
    $name = preg_replace('/^\s+|\s+$/u', '', $input->name);
    $length = preg_match_all('/./us', $name);
    if ($length < 1 || $length > 100) {
        respond(422, ['success' => false, 'message' => 'Tên sản phẩm phải có từ 1 đến 100 ký tự sau khi bỏ khoảng trắng hai đầu.']);
    }
    if (!isset($input->price) || !is_int($input->price) || $input->price <= 0 || $input->price > 2147483647) {
        respond(422, ['success' => false, 'message' => 'Đơn giá phải là số nguyên lớn hơn 0 và không vượt quá 2147483647.']);
    }
    if (!isset($input->stock) || !is_int($input->stock) || $input->stock < 0 || $input->stock > 2147483647) {
        respond(422, ['success' => false, 'message' => 'Tồn kho phải là số nguyên không âm và không vượt quá 2147483647.']);
    }
}

try {
    $config = require __DIR__ . '/config.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
    );

    if ($method === 'GET') {
        $products = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id DESC')->fetchAll();
        foreach ($products as &$product) {
            $product['id'] = (int) $product['id'];
            $product['price'] = (int) $product['price'];
            $product['stock'] = (int) $product['stock'];
        }
        unset($product);
        respond(200, ['success' => true, 'data' => $products]);
    }

    if ($method === 'POST') {
        $statement = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (?, ?, ?)');
        $statement->execute([$name, $input->price, $input->stock]);
        respond(201, ['success' => true, 'message' => 'Đã thêm sản phẩm.', 'id' => (int) $pdo->lastInsertId()]);
    }

    // Khóa bản ghi trong giao dịch để việc kiểm tra tồn tại và cập nhật nhất quán.
    $pdo->beginTransaction();
    $statement = $pdo->prepare('SELECT id FROM products WHERE id = ? FOR UPDATE');
    $statement->execute([$id]);
    if (!$statement->fetch()) {
        $pdo->rollBack();
        respond(404, ['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
    }
    if ($method === 'PUT') {
        $statement = $pdo->prepare('UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?');
        $statement->execute([$name, $input->price, $input->stock, $id]);
    } else {
        $statement = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $statement->execute([$id]);
    }
    $pdo->commit();
    respond(200, ['success' => true, 'message' => $method === 'PUT' ? 'Đã cập nhật sản phẩm.' : 'Đã xóa sản phẩm.']);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    respond(500, ['success' => false, 'message' => 'Không thể xử lý dữ liệu. Vui lòng kiểm tra kết nối và cấu hình MySQL.']);
}
