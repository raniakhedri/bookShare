/** @type {import('tailwindcss').Config} */

const plugin = require("tailwindcss/plugin");

module.exports = {
  content: ["*"],
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
        'banner': "url('images/banner-image-bg.jpg')",
        'counter': "url('images/banner-image-bg-1.jpg')",
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
