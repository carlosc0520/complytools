module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'conoce-green': '#5500FF',
        'conoce-gray': '#686868',
        'conoce-blocked': 'rgba(0, 0, 0, 0.4)',
        'conoce-blue': '#007CB0',
        'conoce-darkgray': '#4D4D4D',
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    styled: true,
    /*themes: [
      {
        mytheme: {
          primary: '#81D742'
        }
      }
    ],*/
    themes: true,
    base: true,
    utils: true,
    logs: true,
    rtl: false,
    darkTheme: "dark",
  },
}
