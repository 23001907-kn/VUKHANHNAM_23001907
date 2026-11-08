const currencyFormatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })

export default {
  name: 'TheSanPham',
  props: {
    ten: { type: String, required: true },
    gia: { type: Number, required: true, validator: value => Number.isFinite(value) && value > 0 },
    anh: { type: String, default: './images/placeholder.svg' },
    moTa: { type: String, default: 'Sản phẩm hiện chưa có mô tả.' },
    soLuongTon: { type: Number, default: 0, validator: value => Number.isSafeInteger(value) && value >= 0 },
  },
  emits: ['mua'],
  // Mỗi instance có đối tượng data riêng, không dùng chung trạng thái.
  data() {
    return { hienMoTa: false }
  },
  methods: {
    dinhDangGia(value) {
      return currencyFormatter.format(value)
    },
    mua() {
      if (this.soLuongTon > 0) this.$emit('mua', this.ten)
    },
  },
  template: `
    <article class="product-card">
      <div class="product-image">
        <img :src="anh" :alt="ten" width="400" height="280" />
        <span v-if="soLuongTon === 0" class="badge sold-out">Hết hàng</span>
        <span v-else class="badge available">Còn hàng</span>
      </div>
      <div class="product-body">
        <h2>{{ ten }}</h2>
        <p class="price">{{ dinhDangGia(gia) }}</p>
        <p class="stock">Tồn kho: <strong>{{ soLuongTon }}</strong> sản phẩm</p>
        <button class="description-toggle" type="button" :aria-expanded="hienMoTa" @click="hienMoTa = !hienMoTa">
          {{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }} <span aria-hidden="true">{{ hienMoTa ? '−' : '+' }}</span>
        </button>
        <p v-if="hienMoTa" class="description">{{ moTa }}</p>
        <button class="buy-button" type="button" :disabled="soLuongTon === 0" @click="mua">Mua</button>
      </div>
    </article>
  `,
}
