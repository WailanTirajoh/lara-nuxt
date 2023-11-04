<script setup lang="ts">
import { storeToRefs } from "pinia";

useSeoMeta({
  title: "Login",
});

definePageMeta({
  layout: "guest",
});

const route = useRoute();
const authStore = useAuthStore();
const { accessToken } = storeToRefs(authStore);

onMounted(() => {
  if (!route.query.access_token) {
    window.location.href = "auth/login";
  }

  accessToken.value = route.query.access_token;
  window.location.href = "/";
});
</script>

<template>
  <div class="p-4 border rounded max-w-lg mx-auto bg-white shadow">
    <div class="grid grid-cols-12">Redirecting</div>
  </div>
</template>
