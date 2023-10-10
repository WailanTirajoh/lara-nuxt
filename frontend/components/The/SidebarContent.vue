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

  await authStore.logout();
  authStore.revokeAccessToken();
  authStore.clearProfile();

  navigateTo("/auth/login");
};

const menus = [
  {
    label: "Dashboard",
    icon: "lucide:home",
    to: "/",
    permission: null,
  },
  {
    label: "Post",
    icon: "mdi:post-outline",
    to: "/posts",
    permission: "post-access",
  },
  {
    label: "User",
    icon: "ph:users-three-duotone",
    to: "/users",
    permission: "user-access",
  },
  {
    label: "Roles",
    icon: "material-symbols:shield-lock",
    to: "/roles",
    permission: "role-access",
  },
];
</script>

<template>
  <div class="h-full text-slate-300">
    <div class="h-16 px-4">
      <div v-if="!profile" class="flex gap-4 items-center">
        <div class="skeleton !w-10 !h-8 rounded"></div>
        <div class="flex flex-col gap-3 w-full">
          <div class="skeleton w-24 h-4 rounded"></div>
          <div class="skeleton w-32 h-4 rounded"></div>
        </div>
      </div>
      <div v-else class="flex gap-4 items-center">
        <NuxtImg src="/wailan.jpeg" class="w-8 h-8 rounded border" />
        <div class="">
          <div class="text-lg text-slate-200">
            {{ profile.name }}
          </div>
          <div class="text-sm text-slate-400">
            {{ profile.email }}
          </div>
        </div>
      </div>
    </div>
    <TransitionGroup
      name="list"
      tag="ul"
      class="flex flex-col gap-2 px-4 h-[calc(100%-3rem-4rem)] overflow-auto overflow-x-hidden"
    >
      <template v-for="menu in menus">
        <li
          v-if="menu.permission === null || hasPermissions(menu.permission)"
          :key="menu.to"
          class="relative overflow-hidden"
        >
          <NuxtLink
            class="px-4 py-2 flex items-center duration-300 text-md gap-4 hover:bg-blue-600 rounded before:content-[''] before:absolute before:-left-1 before:w-1 before:h-4 before:bg-blue-200 before:rounded-r before:duration-300"
            active-class="text-blue-100 bg-blue-600 before:!left-0"
            :to="menu.to"
          >
            <Icon :name="menu.icon" class="text-xl" />
            <div>{{ menu.label }}</div>
          </NuxtLink>
        </li>
      </template>
    </TransitionGroup>
    <div class="h-16">
      <ul class="flex flex-col gap-2 px-4 overflow-auto overflow-x-hidden">
        <li class="relative overflow-hidden">
          <BaseButton
            variant="light"
            class="w-full px-4 py-2 flex items-center duration-300 text-md gap-4 hover:text-slate-200 hover:bg-slate-700 rounded before:content-[''] before:absolute before:-left-1 before:w-1 before:h-4 before:bg-blue-200 before:rounded-r before:duration-300"
            @click="logout"
          >
            <Icon name="solar:logout-3-bold-duotone" class="text-xl" />
            <div class="text-center">Logout</div>
          </BaseButton>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
  position: absolute;
}
</style>
