<script setup lang="ts">
import { storeToRefs } from "pinia";

const dialog = useDialog();

const authStore = useAuthStore();
const { profile } = storeToRefs(authStore);

const logout = async () => {
  const res = await dialog.fire({
    title: "Are you sure you want to logout?",
    description: "This will destroy your current session.",
  });

  if (!res) return;

  await useFetchAPI("/auth/logout", {
    method: "delete",
  });

  useAuthStore().revokeAccessToken();

  navigateTo("/auth/login");
};
</script>

<template>
  <div class="h-full">
    <div class="h-16 px-4">
      <div v-if="profile" class="flex gap-2 items-center">
        <NuxtImg src="/wailan.jpeg" class="w-8 h-8 rounded border" />
        <div class="">
          <div class="text-lg text-slate-900">
            {{ profile.name }}
          </div>
          <div class="text-sm text-slate-400">
            {{ profile.email }}
          </div>
        </div>
      </div>
    </div>
    <ul
      class="flex flex-col gap-4 px-4 h-[calc(100%-3rem-4rem)] overflow-auto overflow-x-hidden"
    >
      <li class="flex flex-col gap-2">
        <div class="font-semibold">Management</div>
        <ul class="flex flex-col gap-1">
          <li class="relative overflow-hidden">
            <NuxtLink
              class="px-4 py-2 flex items-center duration-300 text-md gap-2 hover:bg-blue-100 rounded before:content-[''] before:absolute before:-left-1 before:w-1 before:h-4 before:bg-blue-600 before:rounded-r before:duration-300"
              active-class="text-blue-600 bg-blue-100 before:!left-0"
              to="/"
            >
              <Icon name="lucide:home" class="text-xl" />
              <div class="">Home</div>
            </NuxtLink>
          </li>
          <li class="relative overflow-hidden">
            <NuxtLink
              class="px-4 py-2 flex items-center duration-300 text-md gap-2 hover:bg-blue-100 rounded before:content-[''] before:absolute before:-left-1 before:w-1 before:h-4 before:bg-blue-600 before:rounded-r before:duration-300"
              active-class="text-blue-600 bg-blue-100 before:!left-0"
              to="/posts"
            >
              <Icon name="mdi:post-outline" class="text-xl" />
              <div class="">Post</div>
            </NuxtLink>
          </li>
        </ul>
      </li>
      <li class="flex flex-col gap-2">
        <div class="font-semibold">Web Setting</div>
        <ul class="flex flex-col gap-1">
          <li>
            <div
              class="px-4 py-2 flex items-center duration-300 text-md gap-2 hover:bg-blue-100 rounded"
            >
              <Icon name="mdi:tag-outline" class="text-xl" />
              <div class="">Meta</div>
            </div>
          </li>
          <li>
            <div
              class="px-4 py-2 flex items-center duration-300 text-md gap-2 hover:bg-blue-100 rounded"
            >
              <Icon name="fluent:color-24-regular" class="text-xl" />
              <div class="">Color</div>
            </div>
          </li>
        </ul>
      </li>
    </ul>
    <div class="h-16">
      <div class="p-2">
        <BaseButton
          class="flex gap-2 items-center text-lg w-full"
          variant="light"
          @click="logout"
        >
          <Icon name="solar:logout-3-bold-duotone" />
          <div class="text-center">Logout</div>
        </BaseButton>
      </div>
    </div>
  </div>
</template>
