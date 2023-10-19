// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  ssr: false,
  vite: {
    vue: {
      script: {
        defineModel: true,
      },
      template: {
        compilerOptions: {
          isCustomElement: (tag) => tag === "Multi",
        },
      },
    },
  },
  runtimeConfig: {
    public: {
      baseUrl: "https://lara-blog.test/api/v1",
      key: "app-key",
      wsHost: "localhost",
      wsPort: 6001,
      wssPort: 6001,
    },
  },
  plugins: ["~/plugins/laravel-echo"],
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
