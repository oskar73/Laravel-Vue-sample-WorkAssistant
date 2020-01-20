/** @type {import('tailwindcss').Config} */
const { fontFamily, colors } = require('tailwindcss/defaultTheme')

module.exports = {
  prefix: 'tw-',
  content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
  extend: {
    display: ['group-hover']
  },
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...fontFamily.sans],
        serif: ['"Gentium Book Basic"', ...fontFamily.serif]
      },
      colors: {
        ...colors
      },
      gridTemplateColumns: {
        'fill-240': 'repeat(auto-fill, minmax(240px, 1fr))',
        'fill-300': 'repeat(auto-fill, minmax(300px, 1fr))'
      }
    }
  },
  corePlugins: {
    aspectRatio: false
  },
  plugins: [require('@tailwindcss/typography'), require('@tailwindcss/forms'), require('@tailwindcss/aspect-ratio')]
}
