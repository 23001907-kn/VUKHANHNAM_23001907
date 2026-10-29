<script>
const createDefaultForm = () => ({ fullName: '', showtime: '18:00', ticketType: 'standard', quantity: 1, combo: false })

export default {
  data() {
    return {
      movie: { title: 'Hành trình đến những vì sao', genre: 'Khoa học viễn tưởng · Phiêu lưu', duration: '120 phút', rating: 'T13' },
      showtimes: ['15:00', '18:00', '20:30'],
      ticketPrices: { standard: 70000, vip: 100000 },
      comboPrice: 45000,
      form: createDefaultForm(),
      confirmation: null,
    }
  },
  computed: {
    isNameValid() {
      // NFC hỗ trợ cả tiếng Việt được nhập bằng bộ gõ hoặc dán từ nơi khác.
      return /^\p{L}+(?: +\p{L}+)*$/u.test(this.form.fullName.trim().normalize('NFC'))
    },
    isQuantityValid() {
      return Number.isInteger(this.form.quantity) && this.form.quantity >= 1 && this.form.quantity <= 10
    },
    ticketPrice() {
      return this.ticketPrices[this.form.ticketType] ?? 0
    },
    comboTotal() {
      return this.form.combo ? this.comboPrice : 0
    },
    totalPrice() {
      return this.isQuantityValid ? this.ticketPrice * this.form.quantity + this.comboTotal : 0
    },
    canBook() {
      return this.isNameValid && this.isQuantityValid && this.showtimes.includes(this.form.showtime) && this.ticketPrice > 0
    },
  },
  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(amount)
    },
    changeQuantity(change) {
      if (this.isQuantityValid) this.form.quantity = Math.min(10, Math.max(1, this.form.quantity + change))
    },
    resetForm() {
      this.form = createDefaultForm()
      this.confirmation = null
    },
    bookTickets() {
      if (!this.canBook) return
      // Lưu bản chụp đơn đặt vé để việc sửa form không thay đổi xác nhận đã gửi.
      this.confirmation = {
        ...this.form,
        fullName: this.form.fullName.trim(),
        ticketLabel: this.form.ticketType === 'vip' ? 'Vé VIP' : 'Vé thường',
        movieTitle: this.movie.title,
        totalPrice: this.totalPrice,
      }
      this.$nextTick(() => this.$refs.confirmationPanel?.focus())
    },
  },
}
</script>

<template>
  <div class="page-shell">
    <main id="main">
      <div class="page-heading"><p class="eyebrow">MỘT TẤM VÉ, NGÀN TRẢI NGHIỆM</p><h1>Hẹn bạn ở rạp phim<span>.</span></h1><p>Chọn suất chiếu yêu thích, phần còn lại để chúng mình lo.</p></div>
      <div class="workspace">
        <section class="booking-card" aria-labelledby="booking-title">
          <div class="section-title"><span class="step">01</span><div><h2 id="booking-title">Thông tin đặt vé</h2><p>Chuẩn bị cho một buổi xem phim thật vui.</p></div></div>
          <form @submit.prevent="bookTickets">
            <label for="full-name">Họ và tên khách hàng <span class="required">*</span></label>
            <input id="full-name" v-model.trim="form.fullName" autocomplete="name" placeholder="Nhập họ và tên của bạn" maxlength="100" required :aria-invalid="form.fullName.length > 0 && !isNameValid" aria-describedby="name-hint" />
            <p id="name-hint" class="name-hint" :class="{ error: form.fullName.length > 0 && !isNameValid }" aria-live="polite">{{ form.fullName.length > 0 && !isNameValid ? 'Họ tên chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.' : 'Nhập họ tên bằng chữ, hỗ trợ tiếng Việt có dấu.' }}</p>
            <label for="showtime">Suất chiếu</label>
            <select id="showtime" v-model="form.showtime"><option v-for="time in showtimes" :key="time" :value="time">{{ time }} — Phòng chiếu 01</option></select>
            <fieldset><legend>Loại vé</legend><div class="ticket-options">
              <label class="ticket-option" :class="{ selected: form.ticketType === 'standard' }"><input v-model="form.ticketType" type="radio" value="standard" name="ticket-type" /><span><strong>Vé thường</strong><small>{{ formatCurrency(ticketPrices.standard) }} / vé</small></span></label>
              <label class="ticket-option" :class="{ selected: form.ticketType === 'vip' }"><input v-model="form.ticketType" type="radio" value="vip" name="ticket-type" /><span><strong>Vé VIP <span class="vip-badge">VIP</span></strong><small>{{ formatCurrency(ticketPrices.vip) }} / vé</small></span></label>
            </div></fieldset>
            <div class="quantity-heading"><label for="quantity">Số lượng vé</label><span>Từ 1 đến 10 vé</span></div>
            <div class="quantity-control"><button type="button" aria-label="Giảm số lượng vé" @click="changeQuantity(-1)" :disabled="!isQuantityValid || form.quantity <= 1">−</button><input id="quantity" v-model.number="form.quantity" type="number" min="1" max="10" step="1" required :aria-invalid="!isQuantityValid" aria-describedby="quantity-hint" /><button type="button" aria-label="Tăng số lượng vé" @click="changeQuantity(1)" :disabled="!isQuantityValid || form.quantity >= 10">+</button></div>
            <p id="quantity-hint" class="quantity-hint" :class="{ error: !isQuantityValid }">{{ isQuantityValid ? 'Mỗi tấm vé là một trải nghiệm đáng nhớ.' : 'Vui lòng nhập số nguyên từ 1 đến 10.' }}</p>
            <label class="combo-option" :class="{ selected: form.combo }"><input v-model="form.combo" type="checkbox" /><span><strong>Thêm combo bắp & nước</strong><small>01 bắp + 01 nước · Áp dụng một lần cho mỗi đơn</small></span><b>+45.000 ₫</b></label>
            <div class="form-actions"><button class="reset-button" type="button" @click="resetForm">Đặt lại</button><button class="book-button" type="submit" :disabled="!canBook">Đặt vé <span aria-hidden="true">→</span></button></div>
            <p class="form-note">Nhập họ tên và số lượng hợp lệ để bật nút đặt vé.</p>
          </form>
        </section>
        <aside class="summary-card" aria-labelledby="summary-title">
          <div class="movie-art" aria-hidden="true"><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="planet"></div><span class="star star-one">✦</span><span class="star star-two">✧</span><span class="art-caption">BEYOND THE STARS</span><span class="art-bottom">MỘT HÀNH TRÌNH MỚI ĐANG CHỜ BẠN</span></div>
          <div class="summary-body"><p class="eyebrow">PHIM BẠN ĐÃ CHỌN</p><h2>{{ movie.title }}</h2><p class="movie-meta">{{ movie.genre }}</p><div class="movie-tags"><span>{{ movie.rating }}</span><span>{{ movie.duration }}</span><span>2D · Phụ đề</span></div>
            <div class="summary-divider"></div><h3 id="summary-title">Chi tiết thanh toán</h3>
            <dl><div><dt>Suất chiếu</dt><dd>{{ form.showtime }}</dd></div><div><dt>{{ form.ticketType === 'vip' ? 'Vé VIP' : 'Vé thường' }}</dt><dd>{{ formatCurrency(ticketPrice) }} × {{ isQuantityValid ? form.quantity : '—' }}</dd></div><div><dt>Combo bắp nước</dt><dd>{{ form.combo ? formatCurrency(comboTotal) : 'Không chọn' }}</dd></div></dl>
            <div class="total-row"><span>Tổng thanh toán</span><strong aria-live="polite">{{ isQuantityValid ? formatCurrency(totalPrice) : '—' }}</strong></div><p class="summary-note">Giá combo tính theo đơn, không nhân số lượng vé.</p>
          </div>
        </aside>
      </div>
      <section v-if="confirmation" ref="confirmationPanel" class="confirmation" tabindex="-1" aria-labelledby="confirmation-title" role="status">
        <span class="confirmation-icon" aria-hidden="true">✓</span><div><p class="eyebrow">HẸN GẶP BẠN TẠI RẠP</p><h2 id="confirmation-title">Đặt vé thành công!</h2><p>Cảm ơn <strong>{{ confirmation.fullName }}</strong>. Dưới đây là thông tin đơn của bạn.</p><dl><div><dt>Phim</dt><dd>{{ confirmation.movieTitle }}</dd></div><div><dt>Suất chiếu</dt><dd>{{ confirmation.showtime }}</dd></div><div><dt>Vé</dt><dd>{{ confirmation.quantity }} × {{ confirmation.ticketLabel }}</dd></div><div><dt>Combo bắp nước</dt><dd>{{ confirmation.combo ? 'Có · ' + formatCurrency(comboPrice) : 'Không' }}</dd></div><div><dt>Tổng tiền</dt><dd class="confirmation-total">{{ formatCurrency(confirmation.totalPrice) }}</dd></div></dl></div>
      </section>
      
    </main>
  </div>
</template>
