# Bài 1: Danh sách sản phẩm có tìm kiếm và thống kê

Ứng dụng Vue 3 (Options API) và Vite. Có 6 sản phẩm ban đầu thuộc 3 danh mục.

## Chạy ứng dụng

```sh
cd bai1
npm install
npm run dev
```

Mở địa chỉ được Vite hiển thị trong terminal. Trên PowerShell nếu `npm.ps1` bị chặn, dùng `npm.cmd` thay cho `npm`.

## Build

```sh
npm run build
```

- `computed`: kết hợp tìm kiếm, lọc danh mục, sắp xếp và thống kê. Chỉ sắp xếp mảng kết quả lọc, giữ nguyên thứ tự mảng gốc.
- `methods`: định dạng tiền, kiểm tra/thêm sản phẩm, xác nhận/xóa sản phẩm và đặt lại bộ lọc.
- `watch`: hiện thông báo nếu từ khóa có đúng một ký tự, xóa khi rỗng hoặc từ hai ký tự.
- `mounted`: chuyển trạng thái trang thành “Sẵn sàng”.
- Directive: `v-model`, `v-for`, `v-if`, `v-else`, `:key`, `:aria-label`, `@click`, `@submit.prevent`.

Thống kê luôn dựa trên sản phẩm đang hiển thị; khi không có kết quả, số hiển thị và tổng giá trị đều bằng 0. Thêm và xóa cập nhật thống kê ngay. Dữ liệu được giữ trong bộ nhớ và trở về mặc định khi tải lại trang.
