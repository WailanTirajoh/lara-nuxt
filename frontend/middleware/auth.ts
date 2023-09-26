import { storeToRefs } from "pinia";
import { useAuthStore } from "~/stores/auth";

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore();
  const { isAuthenticated } = storeToRefs(authStore);

  if (to.fullPath.startsWith("/auth/login")) return;

  if (!isAuthenticated.value) {
    return navigateTo("/auth/login");
  }
});
