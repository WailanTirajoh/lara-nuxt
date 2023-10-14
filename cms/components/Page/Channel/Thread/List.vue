<script setup lang="ts">
import { storeToRefs } from "pinia";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();

const threadStore = useThreadStore();
const { threads } = storeToRefs(threadStore);

onMounted(() => {
  threadStore.fetchThreads(props.channelId);
});
</script>

<template>
  <div class="p-4 h-full">
    <TransitionGroup name="list" tag="ul" class="flex flex-col gap-4 overflow-hidden h-full">
      <li :key="thread.id" v-for="thread in threads">
        <PageChannelThreadListItem
          :thread="thread"
          :channel-id="props.channelId"
        />
      </li>
    </TransitionGroup>
  </div>
</template>
