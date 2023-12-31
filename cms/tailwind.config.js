/** @type {import('tailwindcss').Config} */
const formKitTailwind = require("@formkit/themes/tailwindcss");
const typography = require("@tailwindcss/typography");

module.exports = {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./nuxt.config.{js,ts}",
    "./app.vue",
    "./tailwind-theme.js",
  ],
  darkMode: "class", // or 'media' or 'class'
  theme: {
    extend: {
      extend: {
        colors: {},
      },
    },
  },
  plugins: [formKitTailwind, typography],
};
