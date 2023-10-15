<script setup lang="ts">
import { storeToRefs } from "pinia";
import type { Channel } from "~/types/api/channel";

const channel = ref<Channel>();

useSeoMeta({
  title: channel.value?.name ?? "Channel",
});

definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();
const channelId = computed<string>(() => route.params.id as string);
const channelStore = useChannelStore();
const authStore = useAuthStore();
const { profile } = storeToRefs(authStore);
const dialog = useDialog();
const threadStore = useThreadStore();
const { threads, selectedThread } = storeToRefs(threadStore);

const destroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;
  await channelStore.destroy(id);
  channelStore.fetchChannels();
  navigateTo("/");
};

const showModalEdit = ref(false);
const onChannelUpdate = async () => {
  await fetchChannel();
};
const fetchChannel = async () => {
  showModalEdit.value = false;
  channelStore.fetchChannels();
  const { data } = await channelStore.show(channelId.value);
  channel.value = data.channel;
};
onMounted(async () => {
  threads.value = [];
  selectedThread.value = undefined;
  await fetchChannel();
});
</script>

<template>
  <div class="h-[calc(100vh)]">
    <div class="h-full">
      <div class="p-4 h-16 flex items-center justify-between">
        <h1 class="text-3xl">
          <template v-if="channel">
            {{ channel.name }}
          </template>
          <template v-else>
            <div class="skeleton !w-32 !h-8"></div>
          </template>
        </h1>
        <div class="">
          <div class="flex gap-1">
            <template v-if="channel">
              <div v-for="user in channel.users">
                <img
                  :src="user.profile_picture"
                  alt=""
                  class="w-6 h-6 rounded-full object-cover object-center"
                />
              </div>
              <template v-if="profile.id === channel.created_by">
                <button
                  class="w-6 h-6 rounded-full group"
                  @click="showModalEdit = !showModalEdit"
                >
                  <Icon
                    name="icon-park-twotone:config"
                    class="group-hover:animate-spin"
                  />
                </button>
                <BaseModal v-model="showModalEdit">
                  <template #title>
                    <div class="text-lg">Edit {{ channel.name }}</div>
                  </template>
                  <PageChannelEdit
                    :channel="channel"
                    @submit="onChannelUpdate"
                  />
                </BaseModal>
              </template>
            </template>
            <template v-else>
              <div v-for="i in 2" class="skeleton !w-8 !h-8 rounded-full"></div>
            </template>
          </div>
        </div>
      </div>
      <div class="relative flex h-full">
        <div
          class="relative h-[calc(100%-4rem)] bg-red-50 shadow-inner w-full duration-300"
          :class="{
            'w-full sm:w-1/2': selectedThread,
          }"
        >
          <div class="h-[calc(100%)] overflow-auto bg-gray-50 shadow-inner">
            <PageChannelThreadList :channel-id="channelId" />
          </div>
          <div
            class="absolute bottom-0 min-h-40 w-full translate-y-32 hover:translate-y-0 duration-300"
          >
            <div class="text-center flex items-center justify-center">
              <div
                class="bg-white p-1 border border-gray-400 border-b-0 rounded-t-lg z-10"
              >
                New Thread
              </div>
            </div>
            <PageChannelThreadCreate :channel-id="channelId" />
          </div>
        </div>
        <!-- Thread View -->
        <div
          class="absolute right-0 w-full sm:w-1/2 translate-x-full h-[calc(100%-4rem)] bg-white shadow duration-300 z-10"
          :class="{
            '!translate-x-0': selectedThread,
          }"
        >
          <button
            v-if="selectedThread"
            class="absolute right-0 sm:right-[unset] sm:-left-8 top-5 w-8 h-8 bg-white border-r-0 border sm:rounded-l-lg flex items-center justify-center z-10"
            @click="selectedThread = undefined"
          >
            <Icon name="ic:baseline-close" />
          </button>
          <PageChannelThreadShow />
        </div>
      </div>
    </div>
  </div>
</template>
