<script setup lang="ts">
useSeoMeta({
  title: "Dashboard",
});
definePageMeta({
  middleware: ["auth"],
});

const authStore = useAuthStore();
const { $echo } = useNuxtApp();
$echo.channel("public").listen("PublicEvent", (e: any) => {
  console.log(e);
});

function test() {
  $useFetchAPI("/test-secret");
}
</script>

<template>
  <div class="p-4 sm:p-8 grid grid-cols-12">
    <div class="col-span-12">
      {{ `thread.${authStore.profile.id}` }}
      <h1 class="text-3xl font-medium">Dashboards</h1>
      <button @click="test" class="bg-red-600 p-2 rounded text-white">
        test
      </button>
    </div>
  </div>
</template>
