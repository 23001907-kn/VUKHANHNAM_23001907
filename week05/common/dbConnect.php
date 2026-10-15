<?php
declare(strict_types=1);

function getDbConnection(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    if (!extension_loaded('mysqli')) {
        http_response_code(500);
        exit('PHP hiện tại chưa bật extension mysqli. Hãy chạy project bằng PHP của XAMPP: ./run.sh');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'root',
            getenv('DB_PASS') ?: '',
            getenv('DB_NAME') ?: 'shopping_cart',
            (int) (getenv('DB_PORT') ?: 3306)
        );
        $connection->set_charset('utf8mb4');
    } catch (mysqli_sql_exception) {
        $connection = null;
        http_response_code(500);
        exit('Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra cấu hình MySQL.');
    }

    return $connection;
}