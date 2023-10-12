export const hasPermissions = (...permissions: string[]) => {
  const authStore = useAuthStore();
  if (!authStore.isAuthenticated) return false;

  return permissions.some((permission) =>
    authStore.profile?.permissions.includes(permission)
  );
};
