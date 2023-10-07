<script setup lang="ts">
import { storeToRefs } from "pinia";

const appStore = useAppStore();
const { sidebarIsOpen } = storeToRefs(appStore);
</script>

<template>
  <aside
    class="group fixed z-10 left-0 bg-gray-50 h-[calc(100vh-3rem)] shadow-inner duration-300 hidden sm:block"
    :class="{
      'w-64': sidebarIsOpen,
      'w-6 hover:w-64': !sidebarIsOpen,
    }"
  >
    <button
      class="absolute -right-6 w-6 h-6 bottom-2 bg-gray-100 text-slate-800 hover:text-white duration-300 rounded-r-full border border-l-0 flex items-center justify-center"
      @click="sidebarIsOpen = !sidebarIsOpen"
    >
      <Icon
        name="ic:baseline-chevron-left"
        :class="{
          'rotate-180': !sidebarIsOpen,
          'rotate-0': sidebarIsOpen,
        }"
      />
    </button>
    <div
      class="h-full py-4 duration-300 text-base text-slate-600 relative"
      :class="{
        'hidden group-hover:block': !sidebarIsOpen,
      }"
    >
      <TheSidebarContent />
    </div>
  </aside>
</template>
