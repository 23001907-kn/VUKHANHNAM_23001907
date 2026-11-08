# Bài 3 · Giỏ hàng với props và $emit

```powershell
cd bai3
npm.cmd install
npm.cmd run dev
```

Build: `npm.cmd run build`. Kiểm tra logic: `node --test tests/cart.test.js`.

- `App.js`: cha giữ dữ liệu, kiểm tra ID và số lượng nguyên từ 1 đến 99 trước khi cập nhật; computed tính tổng số lượng và tổng tiền.
- `components/DongGioHang.js`: nhận props riêng lẻ, tính thành tiền, phát `doi-so-luong(id, soLuongMoi)` và `yeu-cau-xoa(id)`; không sửa props.
- `main.js`: đăng ký component trước khi mount.
- Nút giảm khóa ở 1; nút tăng khóa ở 99. Xóa hết hiện “Giỏ hàng trống”, tổng số lượng và tổng tiền bằng 0.
- Ban đầu: 2 bút × 5.000 đồng + 3 quyển vở × 12.000 đồng = **5 sản phẩm, 46.000 đồng**.
- Dữ liệu nằm trong bộ nhớ và trở về mặc định khi tải lại trang.
