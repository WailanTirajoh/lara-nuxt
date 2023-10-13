<script setup lang="ts">
import { storeToRefs } from "pinia";

const appStore = useAppStore();
const { sidebarIsOpen } = storeToRefs(appStore);
const isOpen = ref(false);
</script>

<template>
  <div>
    <div>
      <TheSidebar />
      <main
        class="shadow-sm h-[calc(100vh)] duration-300 overflow-auto"
        :class="{
          'sm:pl-60': sidebarIsOpen,
          'sm:pl-16': !sidebarIsOpen,
          'pl-60': isOpen,
          'pl-0': !isOpen,
        }"
      >
        <NuxtLoadingIndicator />
        <div
          class="fixed z-10 p-4 sm:p-8 flex flex-col gap-2 bg-white w-full sm:w-[calc(100%-4rem)] h-full sm:rounded-l-2xl duration-300"
          :class="{
            'rounded-l-2xl': isOpen
          }"
        >
          <NuxtPage />
        </div>
      </main>
      <button
        class="sm:-bottom-20 fixed flex items-center justify-center bottom-10 left-4 duration-300 bg-[#3a3a3a] z-50 w-12 h-12 rounded-full text-slate-200"
        :class="{
          'left-72': isOpen,
        }"
        @click="isOpen = !isOpen"
      >
        <Icon
          name="material-symbols:chevron-right"
          :class="{
            'rotate-180 duration-300': isOpen,
          }"
        />
      </button>
    </div>
    <Teleport to="body">
      <BaseDialog />
    </Teleport>
  </div>
</template>