// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: [
    "@nuxtjs/tailwindcss",
    "nuxt-icon",
    "@vueuse/nuxt",
    "@pinia/nuxt",
    "@nuxtjs/google-fonts",
    "@nuxt/image",
    "@formkit/nuxt",
  ],
  css: ["@/assets/css/index.css"],
  pinia: {
    autoImports: ["defineStore"],
  },
  imports: {
    dirs: ["stores"],
  },
  googleFonts: {
    families: {
      Roboto: true,
      Rubik: true,
    },
  },
});