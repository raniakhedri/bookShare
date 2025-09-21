/** @type {import('tailwindcss').Config} */

const plugin = require("tailwindcss/plugin");

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./public/**/*.html",
  ],
  theme: {
    extend: {
      fontFamily: {
        'heading': ['Nunito', 'sans-serif'],
        'sans': ['Nunito', 'sans-serif']
      },
      colors: {
        primary: '#F86D72',
        bodyColor: '#1A1A1A',
      },         
      backgroundImage: {
        'banner': "url('/template/images/banner-image-bg.jpg')",
        'counter': "url('/template/images/banner-image-bg-1.jpg')",
      }
    },
  },
  plugins: [
    plugin(({ addBase, theme }) => {
      addBase({
        html: { color: theme("colors.bodyColor") },
      });
    }),
    require('@tailwindcss/forms'),
  ],
}