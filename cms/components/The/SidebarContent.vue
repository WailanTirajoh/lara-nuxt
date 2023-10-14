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

const showCreateChannelModal = ref(false);
const channelStore = useChannelStore();
const { channels } = storeToRefs(channelStore);
const onChannelCreated = () => {
  channelStore.fetchChannels();
  showCreateChannelModal.value = false;
};
onMounted(() => {
  channelStore.fetchChannels();
});
</script>

<template>
  <div class="h-full text-slate-300 group">
    <div class="h-16 px-4">
      <div v-if="!profile" class="flex gap-4 items-center">
        <div class="skeleton !w-10 !h-8 rounded"></div>
        <div class="flex flex-col gap-3 w-full">
          <div class="skeleton w-24 h-4 rounded"></div>
          <div class="skeleton w-32 h-4 rounded"></div>
        </div>
      </div>
      <div v-else class="flex gap-4 items-center">
        <div class="w-8">
          <NuxtImg
            :src="profile.profile_picture"
            class="!w-8 !h-8 object-cover object-center rounded border"
          />
        </div>
        <div class="w-[calc(100%-2rem)]">
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
      <li key="sidebar-channel-horizontal-rule">
        <hr class="border-slate-700" />
      </li>
      <li key="channel" class="flex justify-start">
        <button
          class="text-xs p-1 px-3 group-hover:px-4 rounded bg-gray-800 flex gap-4 items-center duration-100"
          @click="showCreateChannelModal = true"
        >
          <Icon name="material-symbols:add-rounded" class="text-xl" />
          <div class="hidden group-hover:block">Channel</div>
        </button>
        <BaseModal v-model="showCreateChannelModal" :full-height="false">
          <template #title>
            <div class="text-lg">Create a new Channel</div>
          </template>
          <PageSidebarChannelCreate @submit="onChannelCreated" />
        </BaseModal>
      </li>
      <li
        v-for="channel in channels"
        :key="channel.id"
        class="relative overflow-hidden"
      >
        <NuxtLink
          class="px-4 py-2 flex items-center duration-300 text-md gap-4 hover:bg-blue-600 rounded before:content-[''] before:absolute before:-left-1 before:w-1 before:h-4 before:bg-blue-200 before:rounded-r before:duration-300"
          active-class="text-blue-100 bg-blue-600 before:!left-0"
          :to="`/channels/${channel.id}`"
        >
          <Icon name="uil:channel" class="text-xl" />
          <div>{{ channel.name }}</div>
        </NuxtLink>
      </li>
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
