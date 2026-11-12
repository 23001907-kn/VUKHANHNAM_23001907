# Bài thực hành tuần 08 — Quản lý sản phẩm

Bài làm tích hợp cả 4 câu: MySQL, API PHP PDO, giao diện Vue.js và kiểm tra.

## Chạy bằng XAMPP

1. Bật MySQL trong XAMPP Control Panel.
2. Mở phpMyAdmin và nhập file `database.sql` (chỉ nhập một lần để tránh lặp dữ liệu mẫu). Có thể bật Apache để truy cập phpMyAdmin.
3. Kiểm tra tài khoản MySQL trong `api/config.php`. Mặc định: `127.0.0.1:3306`, database `week08`, user `root`, mật khẩu rỗng.
4. Mở PowerShell tại thư mục bài và chạy:

   ```powershell
   C:\xampp\php\php.exe -S localhost:8000 -t .
   ```

5. Mở http://localhost:8000. Cần Internet để tải Vue từ CDN. Không mở trực tiếp `index.html` bằng `file://`.

Cách khác: chép thư mục bài vào `C:\xampp\htdocs\week08`, bật Apache và MySQL rồi mở http://localhost/week08/.

## API (câu 2)

| Phương thức | URL | Chức năng |
| --- | --- | --- |
| GET | `api/products.php` | Danh sách trong `data` |
| POST | `api/products.php` | Thêm sản phẩm |
| PUT | `api/products.php?id=1` | Sửa sản phẩm |
| DELETE | `api/products.php?id=1` | Xóa sản phẩm |

POST và PUT nhận `Content-Type: application/json`, ví dụ:

```json
{"name":"Bút chì","price":6000,"stock":10}
```

Mọi phản hồi có `success`. Lỗi có `message`. Mã HTTP: 200 thành công, 201 đã thêm, 400 JSON/id sai, 404 không tồn tại, 405 phương thức sai, 422 dữ liệu sai, 500 lỗi máy chủ. Giá và tồn phải là số nguyên JSON, không phải chuỗi; giới hạn trên là 2147483647 theo kiểu INT MySQL. API kiểm tra dữ liệu trước khi kết nối/ghi MySQL và dùng prepared statement cho mọi truy vấn có tham số. PUT giữ nguyên dữ liệu vẫn được coi là thành công.

## Kiểm tra trước khi nộp (câu 4)

Đã kiểm tra trên PHP 8.2.12 và MariaDB 10.4.32 tại máy hiện tại: cú pháp PHP/JavaScript hợp lệ; GET ban đầu có 3 sản phẩm; POST và GET lại có sản phẩm mới; PUT đổi giá thành 7000; POST tồn kho -1 trả HTTP 422 và số bản ghi không đổi; DELETE rồi GET không còn sản phẩm; DELETE mã đã xóa trả HTTP 404. Sản phẩm kiểm tra đã được xóa, còn lại 3 dòng mẫu. Các kiểm tra này thực hiện qua HTTP API; thao tác giao diện và tải lại bằng trình duyệt cần kiểm tra theo bảng sau.

Database `week08` đã được nhập trong phiên làm bài này. Nếu chạy lại trên cùng máy, không cần nhập lại `database.sql`.

| Thao tác | Kết quả mong đợi |
| --- | --- |
| Nhập Bút chì / 6000 / 10, bấm Lưu, tải lại trang | Sản phẩm vẫn tồn tại |
| Bấm Sửa sản phẩm, đổi giá thành 7000, Lưu | Bảng hiển thị 7.000 đồng; tải lại vẫn giữ giá |
| Gửi POST trực tiếp với stock = -1 theo lệnh bên dưới | HTTP 422, success = false; không có bản ghi mới |
| Xóa sản phẩm, đồng ý xác nhận, tải lại trang | Sản phẩm không còn |

Kiểm tra tồn kho âm bằng PowerShell (chạy ở cửa sổ khác trong lúc server hoạt động):

```powershell
$base = 'http://localhost:8000/api/products.php'
$before = (Invoke-RestMethod $base).data.Count
$body = @{ name = 'INVALID_STOCK_TEST'; price = 5000; stock = -1 } | ConvertTo-Json
try {
    Invoke-RestMethod $base -Method Post -ContentType 'application/json; charset=utf-8' -Body $body
} catch {
    $_.ErrorDetails.Message
}
$after = (Invoke-RestMethod $base).data.Count
"So ban ghi truoc: $before; sau: $after"
```

Có thể xác minh trong phpMyAdmin:

```sql
SELECT * FROM products WHERE name = 'INVALID_STOCK_TEST';
-- Kết quả mong đợi: 0 dòng.
```

Kiểm tra thêm: tên rỗng/toàn khoảng trắng hoặc hơn 100 ký tự, giá 0/số âm/số thập phân, tồn kho số thập phân, JSON sai đều bị từ chối. PUT/DELETE với id không tồn tại trả 404. Hủy hộp thoại xác nhận xóa không gửi DELETE. Thành công đều gọi lại GET mà không tải lại trang.
