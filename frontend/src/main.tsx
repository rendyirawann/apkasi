import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'
import PetaHotel from './pages/PetaHotel.tsx'

// Routing ringan tanpa react-router: pilih halaman berdasarkan path.
// (sesuai gaya situs yang sudah pakai <a href> / navigasi penuh)
const path = window.location.pathname.replace(/\/+$/, '')
const Page = path === '/peta-hotel' ? PetaHotel : App

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <Page />
  </StrictMode>,
)
