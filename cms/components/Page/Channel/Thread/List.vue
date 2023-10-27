<script setup lang="ts">
import { storeToRefs } from "pinia";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();

const initialFetch = ref(false);
const threadStore = useThreadStore();
const { threads } = storeToRefs(threadStore);
const { $echo } = useNuxtApp();

onMounted(async () => {
  initialFetch.value = true;
  await threadStore.fetchThreads(props.channelId);
  initialFetch.value = false;

  $echo
    .private(`channel.${props.channelId}`)
    .listen(".thread.created", (e: any) => {
      threads.value = [...threads.value, e.thread];
    });
});

onUnmounted(() => {
  $echo.private(`channel.${props.channelId}`).stopListening(".thread.created");
});
</script>

<template>
  <div class="h-full overflow-auto">
    <template v-if="initialFetch">
      <PageChannelThreadListSkeleton />
    </template>
    <template v-else>
      <TransitionGroup
        name="list"
        tag="ul"
        class="flex flex-col-reverse gap-4 overflow-x-hidden h-full p-4"
      >
        <li :key="thread.id" v-for="thread in threads">
          <PageChannelThreadListItem
            :thread="thread"
            :channel-id="props.channelId"
          />
        </li>
      </TransitionGroup>
    </template>
  </div>
</template>
