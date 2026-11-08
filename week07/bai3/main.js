import { createApp } from 'vue'
import App from './App.js'
import DongGioHang from './components/DongGioHang.js'
import './style.css'

const app = createApp(App)
app.component('dong-gio-hang', DongGioHang)
app.mount('#app')
