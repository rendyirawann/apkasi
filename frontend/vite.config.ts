import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    open: true,
    // Proxy panggilan /api ke backend Laravel (php artisan serve) supaya tanpa CORS saat dev.
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:2707',
        changeOrigin: true,
      },
    },
  },
})
