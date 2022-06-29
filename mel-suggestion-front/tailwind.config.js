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
        'dark-blue': '#1f2933',
        'light-blue': '#323f4b',
        'title-blue': '#96b9e7',
        'common-blue': '#c7dfff',
        'light-yellow': '#E1C58F',
        'voted-green': '#11ca8c',
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
