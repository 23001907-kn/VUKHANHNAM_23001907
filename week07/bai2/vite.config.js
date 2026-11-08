import { defineConfig } from 'vite'

// Component trong file .js sử dụng template nên cần bản Vue có compiler.
export default defineConfig({
  resolve: { alias: { vue: 'vue/dist/vue.esm-bundler.js' } },
  define: {
    __VUE_OPTIONS_API__: true,
    __VUE_PROD_DEVTOOLS__: false,
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
  },
})
