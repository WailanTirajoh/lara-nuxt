export const useAuthStore = defineStore("auth", () => {
  const accessToken = useCookie("access_token");

  const isAuthenticated = computed(() => !!accessToken.value)

  return { accessToken, isAuthenticated };
});
