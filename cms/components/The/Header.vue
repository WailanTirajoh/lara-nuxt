<script setup lang="ts">
import { storeToRefs } from "pinia";

const isOpen = ref(false);

const route = useRoute();
const authStore = useAuthStore();

const { width } = useWindowSize();
const { isAuthenticated } = storeToRefs(authStore);

watch(
  () => route.fullPath,
  () => {
    isOpen.value = false;
  }
);

watch(width, (newVal, oldVal) => {
  if (newVal > 640 && oldVal < 640) {
    isOpen.value = false;
  }
});
</script>
<template>
  <header
    class="p-3 bg-white shadow h-12 flex items-center fixed top-0 w-full justify-between"
  >
    <div class="flex gap-2">
      <Icon name="logos:nuxt-icon" class="text-xl" />
      <div class="">Nuxt AdMiny</div>
    </div>
    <div v-if="isAuthenticated" class="block sm:hidden">
      <button @click="isOpen = !isOpen">
        <Icon name="ci:hamburger-md" />
      </button>
      <BaseOffcanvas v-model:is-open="isOpen" position="left">
        <template #header>
          <div class=""></div>
        </template>
        <div class="h-full py-4 duration-300 text-base text-slate-600 relative">
          <TheSidebarContent />
        </div>
      </BaseOffcanvas>
    </div>
  </header>
</template>
