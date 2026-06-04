/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', '"Inter"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
        display: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
      },
      colors: {
        apkasi: {
          dark:    '#1f2a1d',
          medium:  '#2d3a2a',
          hover:   '#2a3827',
          body:    '#4b5b47',
          heading: '#336443',
          accent:  '#85AB8B',
          cta:     '#3d5638',
          ctahov:  '#2d4228',
          gold:    '#D4AF37',
          goldlt:  '#FFE07D',
          cream:   '#fdfcf8',
          leaf:    '#e8f0ea',
        }
      },
      animation: {
        'fade-up': 'fadeUp 0.7s ease-out forwards',
        'fade-in': 'fadeIn 0.5s ease-out forwards',
      },
      keyframes: {
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(24px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}
