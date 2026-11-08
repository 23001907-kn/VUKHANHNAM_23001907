import test from 'node:test'
import assert from 'node:assert/strict'
import App from '../App.js'
import DongGioHang from '../components/DongGioHang.js'

function createCart() {
  const cart = App.data()
  for (const [key, method] of Object.entries(App.methods)) cart[key] = method.bind(cart)
  for (const [key, getter] of Object.entries(App.computed)) {
    Object.defineProperty(cart, key, { get: () => getter.call(cart) })
  }
  return cart
}

test('initial totals match the exercise', () => {
  const cart = createCart()
  assert.equal(cart.tongSoLuong, 5)
  assert.equal(cart.tongTien, 46000)
})

test('parent accepts boundaries and rejects invalid quantities and IDs', () => {
  const cart = createCart()
  for (const value of [0, 100, -1, 1.5, '2', NaN, Infinity, null]) {
    cart.capNhatSoLuong(1, value)
    assert.equal(cart.gioHang[0].soLuong, 2)
  }
  cart.capNhatSoLuong(999, 4)
  cart.capNhatSoLuong('1', 4)
  assert.equal(cart.tongSoLuong, 5)
  cart.capNhatSoLuong(1, 99)
  assert.equal(cart.tongSoLuong, 102)
  assert.equal(cart.tongTien, 531000)
  cart.capNhatSoLuong(1, 1)
  assert.equal(cart.tongTien, 41000)
})

test('child emits identifiers and quantities without mutating props', () => {
  const events = []
  const child = Object.freeze({ id: 1, ten: 'Test', donGia: 5000, soLuong: 2, $emit: (...args) => events.push(args) })
  DongGioHang.methods.doiSoLuong.call(child, 3)
  DongGioHang.methods.doiSoLuong.call(child, 0)
  DongGioHang.methods.doiSoLuong.call(child, 100)
  DongGioHang.methods.yeuCauXoa.call(child)
  assert.deepEqual(events, [['doi-so-luong', 1, 3], ['yeu-cau-xoa', 1]])
  assert.equal(child.soLuong, 2)
  assert.equal(DongGioHang.computed.thanhTien.call(child), 10000)
})

test('deleting all items gives zero totals', () => {
  const cart = createCart()
  cart.xoaSanPham('1')
  cart.xoaSanPham(999)
  assert.equal(cart.gioHang.length, 2)
  cart.xoaSanPham(1)
  assert.equal(cart.tongSoLuong, 3)
  assert.equal(cart.tongTien, 36000)
  cart.xoaSanPham(2)
  assert.equal(cart.gioHang.length, 0)
  assert.equal(cart.tongSoLuong, 0)
  assert.equal(cart.tongTien, 0)
})
