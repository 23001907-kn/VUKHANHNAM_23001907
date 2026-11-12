const { createApp } = Vue;

createApp({
  data() {
    return { products: [], form: { name: '', price: '', stock: 0 }, editingId: null, message: '', isError: false, loading: false, busy: false };
  },
  mounted() { this.loadProducts().catch(error => this.showMessage(error.message, true)); },
  methods: {
    formatPrice(value) { return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value); },
    showMessage(message, isError = false) { this.message = message; this.isError = isError; },
    async request(url = 'api/products.php', options = {}) {
      const response = await fetch(url, { cache: 'no-store', ...options });
      let result;
      try { result = await response.json(); } catch { throw new Error('Máy chủ không trả về JSON hợp lệ. Hãy chạy trang qua PHP.'); }
      if (!response.ok || !result.success) throw new Error(result.message || 'Yêu cầu thất bại.');
      return result;
    },
    async loadProducts() {
      this.loading = true;
      try { this.products = (await this.request()).data; }
      finally { this.loading = false; }
    },
    resetForm() { this.editingId = null; this.form = { name: '', price: '', stock: 0 }; },
    editProduct(product) {
      this.editingId = product.id;
      this.form = { name: product.name, price: product.price, stock: product.stock };
      this.message = '';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    async saveProduct() {
      if (this.busy) return;
      const payload = { name: this.form.name.trim(), price: this.form.price, stock: this.form.stock };
      if (!payload.name || [...payload.name].length > 100 || !Number.isInteger(payload.price) || payload.price <= 0 || payload.price > 2147483647 || !Number.isInteger(payload.stock) || payload.stock < 0 || payload.stock > 2147483647) {
        this.showMessage('Tên phải có 1–100 ký tự; giá là số nguyên dương; tồn kho là số nguyên không âm (tối đa 2147483647).', true);
        return;
      }
      this.busy = true;
      try {
        const editing = this.editingId !== null;
        const result = await this.request('api/products.php' + (editing ? '?id=' + this.editingId : ''), { method: editing ? 'PUT' : 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        this.resetForm();
        this.showMessage(result.message);
        await this.loadProducts();
      } catch (error) { this.showMessage(error.message, true); }
      finally { this.busy = false; }
    },
    async deleteProduct(product) {
      if (this.busy || !window.confirm(`Bạn có chắc muốn xóa sản phẩm "${product.name}"?`)) return;
      this.busy = true;
      try {
        const result = await this.request('api/products.php?id=' + product.id, { method: 'DELETE' });
        if (this.editingId === product.id) this.resetForm();
        this.showMessage(result.message);
        await this.loadProducts();
      } catch (error) { this.showMessage(error.message, true); }
      finally { this.busy = false; }
    }
  }
}).mount('#app');
