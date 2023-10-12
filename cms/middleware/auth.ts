import { storeToRefs } from "pinia";
import { useAuthStore } from "~/stores/auth";

export default defineNuxtRouteMiddleware(async (to, from) => {
  const authStore = useAuthStore();
  const { isAuthenticated, profile } = storeToRefs(authStore);

  if (to.fullPath.startsWith("/auth/login")) return;

  if (!isAuthenticated.value) {
    return navigateTo("/auth/login");
  }

  if (!profile.value) {
    await authStore.getUserProfile();

    const permissions = to.meta.permissions as string[];
    if (permissions) {
      if (!hasPermissions(...permissions)) {
        return navigateTo("/");
      }
    }
  }
});
