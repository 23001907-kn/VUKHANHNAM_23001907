import { currency } from './utils/currency.js'

export default {
  data() {
    return {
      gioHang: [
        { id: 1, ten: 'Bút bi', donGia: 5000, soLuong: 2 },
        { id: 2, ten: 'Quyển vở', donGia: 12000, soLuong: 3 },
      ],
      thongBao: '',
    }
  },
  computed: {
    tongSoLuong() {
      return this.gioHang.reduce((total, item) => total + item.soLuong, 0)
    },
    tongTien() {
      return this.gioHang.reduce((total, item) => total + item.donGia * item.soLuong, 0)
    },
  },
  methods: {
    currency,
    capNhatSoLuong(id, soLuongMoi) {
      // Cha kiểm tra lại dữ liệu trước khi cập nhật nguồn dữ liệu duy nhất.
      if (!Number.isInteger(id) || !Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) {
        this.thongBao = 'Số lượng phải là số nguyên từ 1 đến 99.'
        return
      }
      const sanPham = this.gioHang.find(item => item.id === id)
      if (!sanPham) {
        this.thongBao = 'Không tìm thấy sản phẩm trong giỏ hàng.'
        return
      }
      sanPham.soLuong = soLuongMoi
      this.thongBao = `Đã cập nhật số lượng ${sanPham.ten} thành ${soLuongMoi}.`
    },
    xoaSanPham(id) {
      if (!Number.isInteger(id)) {
        this.thongBao = 'Mã sản phẩm không hợp lệ.'
        return
      }
      const sanPham = this.gioHang.find(item => item.id === id)
      if (!sanPham) {
        this.thongBao = 'Không tìm thấy sản phẩm trong giỏ hàng.'
        return
      }
      this.gioHang = this.gioHang.filter(item => item.id !== id)
      this.thongBao = `Đã xóa ${sanPham.ten} khỏi giỏ hàng.`
    },
  },
  template: `
    <main class="container">
      <section class="card" aria-labelledby="cart-heading">
        <div class="section-heading"><h1 id="cart-heading">Giỏ hàng</h1><span>{{ gioHang.length }} loại sản phẩm</span></div>
        <div v-if="gioHang.length" class="table-wrap">
          <table>
            <thead><tr><th>Sản phẩm</th><th class="number">Đơn giá</th><th>Số lượng</th><th class="number">Thành tiền</th><th><span class="sr-only">Thao tác</span></th></tr></thead>
            <tbody>
              <dong-gio-hang
                v-for="item in gioHang"
                :key="item.id"
                :id="item.id"
                :ten="item.ten"
                :don-gia="item.donGia"
                :so-luong="item.soLuong"
                @doi-so-luong="capNhatSoLuong"
                @yeu-cau-xoa="xoaSanPham"
              ></dong-gio-hang>
            </tbody>
          </table>
        </div>
        <p v-else class="empty">Giỏ hàng trống</p>
        <div class="summary" aria-live="polite">
          <div><span>Tổng số lượng</span><strong>{{ tongSoLuong }} <small>sản phẩm</small></strong></div>
          <div class="grand-total"><span>Tổng tiền</span><strong>{{ currency(tongTien) }}</strong></div>
        </div>
      </section>
      <p class="notification" role="status">{{ thongBao }}</p>
    </main>
  `,
}
