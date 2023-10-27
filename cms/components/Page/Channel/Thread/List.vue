<script setup lang="ts">
import { storeToRefs } from "pinia";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();

const threadStore = useThreadStore();
const { threads } = storeToRefs(threadStore);
const { $echo } = useNuxtApp();

const { pending, execute } = await useAsyncData(
  `channel-${props.channelId}:thread-list`,
  () => {
    return threadStore.fetchThreads(props.channelId);
  },
  {
    immediate: false,
  }
);

onMounted(async () => {
  execute();
  $echo
    .private(`channel.${props.channelId}`)
    .listen(".thread.created", (e: any) => {
      threads.value = [e.thread, ...threads.value];
    });
});

onUnmounted(() => {
  $echo.private(`channel.${props.channelId}`).stopListening(".thread.created");
});
</script>

<template>
  <div class="h-full overflow-auto">
    <template v-if="pending">
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
