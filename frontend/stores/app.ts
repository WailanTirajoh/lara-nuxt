export const useAppStore = defineStore("app", () => {
  const sidebarIsOpen = ref(false);

  return { sidebarIsOpen };
});
