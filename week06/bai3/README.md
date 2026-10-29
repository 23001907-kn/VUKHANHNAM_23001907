# Bài 3 — Quản lý công việc cá nhân

```powershell
cd bai3
npm.cmd install
npm.cmd run dev
```

- `src/App.vue`: dữ liệu, computed, methods và giao diện Vue.
- `src/main.css`: giao diện xanh biển và trắng, khung main có viền tối.
- Thêm bằng nút Thêm hoặc Enter qua `@submit.prevent`.
- Danh sách dùng `v-for` và `:key="task.id"`; checkbox dùng `v-model`.
- Xóa từng việc dùng `@click.stop`; lọc không làm mất dữ liệu.
- Không nhận tên rỗng/toàn khoảng trắng. Tên công việc tối đa 200 ký tự.
- Dữ liệu trong bộ nhớ, tải lại trang sẽ đặt lại.

Kiểm tra: thêm hai việc, hoàn thành một → tổng 2, còn lại 1. Thử ba bộ lọc, xóa việc hoàn thành và thêm lại để kiểm tra id không trùng.

Build: `npm.cmd run build`.
