<script setup lang="ts">
import { storeToRefs } from "pinia";

const appStore = useAppStore();
const { sidebarIsOpen } = storeToRefs(appStore);
</script>

<template>
  <aside
    class="group fixed w-64 z-10 left-0 bg-[#1a1a1a] h-[calc(100vh)] shadow-inner duration-300"
    @mouseover="sidebarIsOpen = true"
    @mouseleave="sidebarIsOpen = false"
  >
    <div
      class="h-full py-4 pr-6 duration-300 text-base text-slate-600 relative"
    >
      <TheSidebarContent />
    </div>
  </aside>
</template>
