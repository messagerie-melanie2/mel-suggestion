module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  purge: [],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        'dark-blue' : '#1f2933',
        'light-blue' : '#323f4b',
        'title-blue' : '#96b9e7',
        'light-yellow' : '#E1C58F',
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
