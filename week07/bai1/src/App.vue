<script>
const currencyFormatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
const emptyForm = () => ({ name: '', price: '', category: 'Điện tử', stock: '' })

export default {
  data() {
    return {
      pageStatus: 'Đang tải',
      categories: ['Điện tử', 'Văn phòng phẩm', 'Gia dụng'],
      products: [
        { id: 1, name: 'Tai nghe Bluetooth', price: 450000, category: 'Điện tử', stock: 12 },
        { id: 2, name: 'Chuột không dây', price: 250000, category: 'Điện tử', stock: 0 },
        { id: 3, name: 'Sổ tay A5', price: 45000, category: 'Văn phòng phẩm', stock: 30 },
        { id: 4, name: 'Bút bi xanh', price: 5000, category: 'Văn phòng phẩm', stock: 100 },
        { id: 5, name: 'Bình giữ nhiệt', price: 180000, category: 'Gia dụng', stock: 8 },
        { id: 6, name: 'Đèn bàn học', price: 320000, category: 'Gia dụng', stock: 0 },
      ],
      nextId: 7,
      keyword: '',
      selectedCategory: '',
      sortOrder: '',
      keywordNotice: '',
      form: emptyForm(),
      errors: {},
      successMessage: '',
    }
  },
  computed: {
    // Lọc kết hợp và sao chép mảng trước khi sort để giữ nguyên dữ liệu gốc.
    displayedProducts() {
      const keyword = this.keyword.trim().toLocaleLowerCase('vi-VN')
      const result = this.products.filter(product =>
        product.name.toLocaleLowerCase('vi-VN').includes(keyword) &&
        (!this.selectedCategory || product.category === this.selectedCategory),
      )
      if (this.sortOrder === 'asc') result.sort((a, b) => a.price - b.price)
      if (this.sortOrder === 'desc') result.sort((a, b) => b.price - a.price)
      return result
    },
    productCount() {
      return `${this.displayedProducts.length} / ${this.products.length}`
    },
    inventoryValue() {
      return this.displayedProducts.reduce((total, product) => total + product.price * product.stock, 0)
    },
  },
  watch: {
    keyword(value) {
      this.keywordNotice = Array.from(value).length === 1
        ? 'Từ khóa chỉ có một ký tự. Nhập thêm để tìm kiếm chính xác hơn.'
        : ''
    },
  },
  mounted() {
    this.pageStatus = 'Sẵn sàng'
  },
  methods: {
    currency(value) {
      return currencyFormatter.format(value)
    },
    addProduct() {
      this.errors = {}
      this.successMessage = ''
      const name = this.form.name.trim()
      const price = Number(this.form.price)
      const stock = Number(this.form.stock)
      if (!name) this.errors.name = 'Vui lòng nhập tên sản phẩm.'
      if (String(this.form.price).trim() === '' || !Number.isFinite(price) || price <= 0) {
        this.errors.price = 'Giá phải là số lớn hơn 0.'
      }
      if (String(this.form.stock).trim() === '' || !Number.isSafeInteger(stock) || stock < 0) {
        this.errors.stock = 'Tồn kho phải là số nguyên không âm.'
      }
      if (!this.categories.includes(this.form.category)) this.errors.category = 'Danh mục không hợp lệ.'
      if (Object.keys(this.errors).length) return
      this.products.push({ id: this.nextId++, name, price, category: this.form.category, stock })
      this.form = emptyForm()
      this.successMessage = `Đã thêm “${name}”. Sản phẩm sẽ hiển thị nếu khớp bộ lọc hiện tại.`
    },
    deleteProduct(product) {
      if (window.confirm(`Bạn có chắc muốn xóa sản phẩm “${product.name}”?`)) {
        this.products = this.products.filter(item => item.id !== product.id)
        this.successMessage = `Đã xóa “${product.name}”.`
      }
    },
    resetFilters() {
      this.keyword = ''
      this.selectedCategory = ''
      this.sortOrder = ''
    },
  },
}
</script>

<template>
  <main class="container">
    <section class="stats" aria-label="Thống kê theo bộ lọc">
      <article class="card"><p>Sản phẩm hiển thị / tổng số</p><strong>{{ productCount }}</strong><small>Khớp với tìm kiếm và danh mục hiện tại</small></article>
      <article class="card"><p>Tổng giá trị tồn kho đang hiển thị</p><strong>{{ currency(inventoryValue) }}</strong><small>Tổng giá × số lượng tồn của các sản phẩm đang hiển thị</small></article>
    </section>

    <section class="card product-panel" aria-labelledby="products-heading">
      <div class="section-heading"><h2 id="products-heading">Danh sách sản phẩm</h2><button class="text-button" @click="resetFilters">Đặt lại bộ lọc</button></div>
      <div class="filters">
        <label class="search">Tìm theo tên<input v-model="keyword" type="search" placeholder="Nhập tên sản phẩm…" aria-describedby="keyword-notice" /></label>
        <label>Danh mục<select v-model="selectedCategory"><option value="">Tất cả danh mục</option><option v-for="category in categories" :key="category" :value="category">{{ category }}</option></select></label>
        <label>Sắp xếp theo giá<select v-model="sortOrder"><option value="">Thứ tự ban đầu</option><option value="asc">Giá tăng dần</option><option value="desc">Giá giảm dần</option></select></label>
      </div>
      <p id="keyword-notice" class="notice" aria-live="polite"><span v-if="keywordNotice">{{ keywordNotice }}</span></p>
      <div class="table-wrap"><table>
        <thead><tr><th>ID</th><th>Tên sản phẩm</th><th>Danh mục</th><th class="number">Giá</th><th class="number">Tồn kho</th><th>Trạng thái</th><th><span class="sr-only">Thao tác</span></th></tr></thead>
        <tbody>
          <tr v-for="product in displayedProducts" :key="product.id">
            <td class="muted">#{{ product.id }}</td><td class="product-name">{{ product.name }}</td><td>{{ product.category }}</td><td class="number">{{ currency(product.price) }}</td><td class="number">{{ product.stock }}</td>
            <td><span v-if="product.stock > 0" class="badge in-stock">Còn hàng</span><span v-else class="badge out-stock">Hết hàng</span></td>
            <td><button class="delete-button" :aria-label="`Xóa ${product.name}`" @click="deleteProduct(product)">Xóa</button></td>
          </tr>
          <tr v-if="displayedProducts.length === 0"><td colspan="7" class="empty">Không có sản phẩm phù hợp với bộ lọc hiện tại.</td></tr>
        </tbody>
      </table></div>
    </section>

    <section class="card add-panel" aria-labelledby="add-heading">
      <h2 id="add-heading">Thêm sản phẩm mới</h2><p class="subtitle">Điền đầy đủ thông tin bên dưới để thêm vào danh sách.</p>
      <form novalidate @submit.prevent="addProduct">
        <div class="form-grid">
          <label>Tên sản phẩm<input v-model="form.name" placeholder="Ví dụ: Bàn phím không dây" required :aria-invalid="!!errors.name" /><span v-if="errors.name" class="error">{{ errors.name }}</span></label>
          <label>Giá (VNĐ)<input v-model="form.price" type="number" min="0.01" step="any" placeholder="0" required :aria-invalid="!!errors.price" /><span v-if="errors.price" class="error">{{ errors.price }}</span></label>
          <label>Danh mục<select v-model="form.category"><option v-for="category in categories" :key="category" :value="category">{{ category }}</option></select><span v-if="errors.category" class="error">{{ errors.category }}</span></label>
          <label>Số lượng tồn<input v-model="form.stock" type="number" min="0" step="1" placeholder="0" required :aria-invalid="!!errors.stock" /><span v-if="errors.stock" class="error">{{ errors.stock }}</span></label>
        </div>
        <div class="form-footer"><p class="success" role="status">{{ successMessage }}</p><button class="primary" type="submit">+ Thêm sản phẩm</button></div>
      </form>
    </section>
    <footer>Directive · Methods · Computed · Watch · Mounted</footer>
  </main>
</template>
