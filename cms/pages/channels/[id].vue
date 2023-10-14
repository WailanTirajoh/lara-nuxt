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
const dialog = useDialog();
const threadStore = useThreadStore();
const { threads } = storeToRefs(threadStore);

const destroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;
  await channelStore.destroy(id);
  channelStore.fetchChannels();
  navigateTo("/");
};

onMounted(async () => {
  threads.value = [];
  const { data } = await channelStore.show(channelId.value);
  channel.value = data.channel;
});
</script>

<template>
  <div class="h-[calc(100vh-4rem)]">
    <div class="h-full" v-if="channel">
      <div class="p-4 h-16 flex items-center justify-between">
        <h1 class="text-3xl">
          {{ channel.name }}
        </h1>
        <div class="">
          <div class="flex gap-1">
            <div v-for="user in channel.users">
              <img
                :src="user.profile_picture"
                alt=""
                class="w-6 h-6 rounded-full object-cover object-center"
              />
            </div>
            <button class="w-6 h-6 rounded-full">
              <Icon name="material-symbols:add-circle-rounded" />
            </button>
          </div>
        </div>
      </div>
      <div class="relative h-[calc(100%-4rem)] bg-gray-50 shadow-inner">
        <div class="h-[calc(100%-10rem)] overflow-auto bg-gray-50 shadow-inner">
          <PageChannelThreadList :channel-id="channelId" />
        </div>
        <div class="absolute bottom-0 min-h-40 w-full">
          <PageChannelThreadCreate :channel-id="channelId" />
        </div>
      </div>
    </div>
  </div>
</template>
