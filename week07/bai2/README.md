# Bài 2 · Danh mục sản phẩm bằng component

Chạy trong thư mục `bai2`:

```powershell
npm.cmd install
npm.cmd run dev
```

Build: `npm.cmd run build`.

Cha trong `main.js` quản lý 4 sản phẩm và truyền props bằng `:ten`, `:gia`, `:anh`, `:mo-ta`, `:so-luong-ton`.

`components/TheSanPham.js` khai báo kiểu cho cả 5 props; `ten` và `gia` bắt buộc; ảnh, mô tả, tồn kho có mặc định. `data()` trả về `hienMoTa` riêng cho từng thẻ. Sản phẩm hết hàng có nhãn và nút Mua bị disabled. Nút Mua còn hàng gửi sự kiện để cha hiện thông báo minh họa.

Component được import và đăng ký bằng `app.component('the-san-pham', TheSanPham)` trước `app.mount('#app')`. Cấu hình Vite dùng Vue có compiler để biên dịch template trong file JavaScript. Ảnh SVG nằm cục bộ, không cần dịch vụ ảnh bên ngoài.
