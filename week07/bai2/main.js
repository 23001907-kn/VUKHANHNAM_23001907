import { createApp } from 'vue'
import TheSanPham from './components/TheSanPham.js'
import './style.css'

const app = createApp({
  data() {
    return {
      thongBao: '',
      sanPham: [
        { id: 1, ten: 'Tai nghe không dây', gia: 450000, anh: './images/headphones.svg', moTa: 'Tai nghe Bluetooth với đệm tai mềm, thiết kế gọn nhẹ và âm thanh rõ ràng. Phù hợp để học tập, nghe nhạc và làm việc hằng ngày.', soLuongTon: 12 },
        { id: 2, ten: 'Bàn phím cơ', gia: 790000, anh: './images/keyboard.svg', moTa: 'Bàn phím cơ nhỏ gọn với cảm giác gõ êm và kết nối USB tiện lợi. Bố cục tối giản giúp bàn làm việc luôn gọn gàng.', soLuongTon: 6 },
        { id: 3, ten: 'Chuột không dây', gia: 250000, anh: './images/mouse.svg', moTa: 'Chuột không dây vừa tay, thao tác chính xác và dễ mang theo. Kết nối nhanh qua đầu thu USB.', soLuongTon: 0 },
        { id: 4, ten: 'Đèn bàn học', gia: 320000, anh: './images/lamp.svg', moTa: 'Đèn bàn có tay đèn điều chỉnh linh hoạt và ánh sáng dịu. Thiết kế thanh lịch phù hợp góc học tập hoặc bàn làm việc.', soLuongTon: 8 },
      ],
    }
  },
  methods: {
    thongBaoMua(ten) {
      this.thongBao = `Bạn đã chọn mua “${ten}”. Đây là thao tác minh họa, chưa tạo đơn hàng.`
    },
  },
  template: `
    <main class="container">
      <div class="section-heading"><h2>Danh mục sản phẩm</h2><span>Thiết bị & phụ kiện</span></div>
      <section class="product-grid" aria-label="Danh sách sản phẩm">
        <the-san-pham
          v-for="item in sanPham"
          :key="item.id"
          :ten="item.ten"
          :gia="item.gia"
          :anh="item.anh"
          :mo-ta="item.moTa"
          :so-luong-ton="item.soLuongTon"
          @mua="thongBaoMua"
        ></the-san-pham>
      </section>
      <p class="notification" role="status">{{ thongBao }}</p>
    </main>
  `,
})

// Import và đăng ký component trước khi mount ứng dụng.
app.component('the-san-pham', TheSanPham)
app.mount('#app')
