import { currency } from '../utils/currency.js'

export default {
  name: 'DongGioHang',
  props: {
    id: { type: Number, required: true },
    ten: { type: String, required: true },
    donGia: { type: Number, required: true, validator: value => Number.isFinite(value) && value > 0 },
    soLuong: { type: Number, required: true, validator: value => Number.isInteger(value) && value >= 1 && value <= 99 },
  },
  emits: {
    'doi-so-luong': (id, soLuongMoi) => Number.isInteger(id) && Number.isInteger(soLuongMoi) && soLuongMoi >= 1 && soLuongMoi <= 99,
    'yeu-cau-xoa': id => Number.isInteger(id),
  },
  computed: {
    thanhTien() {
      return this.donGia * this.soLuong
    },
  },
  methods: {
    currency,
    doiSoLuong(soLuongMoi) {
      // Chỉ gửi yêu cầu; component con không thay đổi props.
      if (Number.isInteger(soLuongMoi) && soLuongMoi >= 1 && soLuongMoi <= 99) {
        this.$emit('doi-so-luong', this.id, soLuongMoi)
      }
    },
    yeuCauXoa() {
      this.$emit('yeu-cau-xoa', this.id)
    },
  },
  template: `
    <tr>
      <td><strong class="product-name">{{ ten }}</strong><span class="product-id">Mã sản phẩm #{{ id }}</span></td>
      <td class="number">{{ currency(donGia) }}</td>
      <td>
        <div class="quantity" :aria-label="'Số lượng ' + ten">
          <button type="button" :disabled="soLuong <= 1" :aria-label="'Giảm số lượng ' + ten" @click="doiSoLuong(soLuong - 1)">−</button>
          <output :aria-label="'Số lượng hiện tại của ' + ten">{{ soLuong }}</output>
          <button type="button" :disabled="soLuong >= 99" :aria-label="'Tăng số lượng ' + ten" @click="doiSoLuong(soLuong + 1)">+</button>
        </div>
      </td>
      <td class="number line-total">{{ currency(thanhTien) }}</td>
      <td class="number"><button type="button" class="delete-button" :aria-label="'Xóa ' + ten" @click="yeuCauXoa">Xóa</button></td>
    </tr>
  `,
}
