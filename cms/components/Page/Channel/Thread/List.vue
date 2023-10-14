<script setup lang="ts">
import { storeToRefs } from "pinia";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();

const threadStore = useThreadStore();
const { threads } = storeToRefs(threadStore);
const initialFetch = ref(false);
onMounted(async () => {
  initialFetch.value = true;
  await threadStore.fetchThreads(props.channelId);
  initialFetch.value = false;
});
</script>

<template>
  <div class="p-4 h-full">
    <template v-if="initialFetch">
      <ul class="flex flex-col gap-4 overflow-hidden h-full">
        <li :key="`skeleton-${i}`" v-for="i in 2">
          <div class="relative flex gap-2 group overflow-hidden">
            <span class="skeleton !w-8 !h-8 rounded-lg"></span>
            <div
              class="prose prose-sm bg-white shadow max-w-full w-full p-2 rounded"
            >
              <div class="text-lg">
                <span class="skeleton !w-32 !h-4"></span>
                <span class="ml-4 text-base text-slate-600">
                  <span class="skeleton !w-20 !h-4"></span>
                </span>
              </div>
              <div class="flex flex-col gap-2 mt-2">
                <div class="w-full flex flex-wrap gap-2">
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-12 !h-4"></span>
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-4 !h-4"></span>
                </div>
                <div class="w-full flex flex-wrap gap-2">
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-12 !h-4"></span>
                </div>
                <div class="w-full flex flex-wrap gap-2">
                  <span class="skeleton !w-12 !h-4"></span>
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-12 !h-4"></span>
                </div>
                <div class="w-full flex flex-wrap gap-2">
                  <span class="skeleton !w-12 !h-4"></span>
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-12 !h-4"></span>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li :key="`skeleton-${i}`" v-for="i in 2">
          <div class="relative flex gap-2 group overflow-hidden">
            <span class="skeleton !w-8 !h-8 rounded-lg"></span>
            <div
              class="prose prose-sm bg-white shadow max-w-full w-full p-2 rounded"
            >
              <div class="text-lg">
                <span class="skeleton !w-32 !h-4"></span>
                <span class="ml-4 text-base text-slate-600">
                  <span class="skeleton !w-20 !h-4"></span>
                </span>
              </div>
              <div class="flex flex-col gap-2 mt-2">
                <div class="w-full flex flex-wrap gap-2">
                  <span class="skeleton !w-12 !h-4"></span>
                  <span class="skeleton !w-64 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-32 !h-4"></span>
                  <span class="skeleton !w-12 !h-4"></span>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </template>
    <template v-else>
      <TransitionGroup
        name="list"
        tag="ul"
        class="flex flex-col gap-4 overflow-hidden h-full"
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