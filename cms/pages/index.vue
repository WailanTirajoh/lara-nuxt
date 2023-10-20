<script setup lang="ts">
useSeoMeta({
  title: "Dashboard",
});
definePageMeta({
  middleware: ["auth"],
});
</script>

<template>
  <div class="p-4 sm:p-8 grid grid-cols-12">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Dashboards</h1>
    </div>
  </div>
</template>
