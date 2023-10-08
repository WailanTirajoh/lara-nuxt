<script setup lang="ts">
import { storeToRefs } from "pinia";

const appStore = useAppStore();
const { sidebarIsOpen } = storeToRefs(appStore);
</script>

<template>
  <div>
    <TheHeader />
    <div class="pt-12">
      <TheSidebar />
      <main
        class="shadow-sm h-[calc(100vh-3rem)] duration-300 overflow-auto"
        :class="{
          'sm:pl-64': sidebarIsOpen,
          'sm:pl-6': !sidebarIsOpen,
        }"
      >
        <NuxtLoadingIndicator />
        <div class="p-4 sm:p-8 flex flex-col gap-2">
          <NuxtPage />
        </div>
      </main>
    </div>
    <Teleport to="body">
      <BaseDialog />
    </Teleport>
  </div>
</template>
