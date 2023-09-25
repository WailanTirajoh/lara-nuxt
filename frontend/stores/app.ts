export const useAppStore = defineStore("app", () => {
  const sidebarIsOpen = ref(true);

  return { sidebarIsOpen };
});
