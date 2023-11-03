<script setup lang="ts">
import type { Thread } from "~/types/api/thread";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();

const { $echo } = useNuxtApp();
const isEndOfThread = ref(false);
const threadStore = useThreadStore();
const threads = ref<Array<Thread>>([]);
const page = ref(1);

const { pending, execute } = await useAsyncData(
  `channel-${props.channelId}:thread-list`,
  () => {
    return fetchThreads();
  },
  {
    immediate: false,
  }
);

const fetchThreads = async () => {
  if (isEndOfThread.value) return;

  const { data } = await threadStore.get(props.channelId, {
    params: {
      page: page.value,
      "thread_ids[]": threads.value.map((thread) => thread.id),
    },
  });

  if (data.threads.length === 0) {
    isEndOfThread.value = true;
    return;
  }

  threads.value = [...threads.value, ...data.threads];
};

const el = ref<HTMLElement | null>(null);
const { arrivedState } = useScroll(el, {
  offset: { top: 30, bottom: 30, right: 30, left: 30 },
});

watch(
  () => arrivedState.top,
  (value) => {
    if (value) {
      execute();
    }
  }
);

onMounted(async () => {
  execute();
  $echo
    .private(`channel.${props.channelId}`)
    .listen(".thread.created", (e: any) => {
      threads.value = [e.thread, ...threads.value];
    })
    .listen(".thread.updated", (e: any) => {
      const index = threads.value.findIndex((t) => t.id === e.thread.id);
      if (index < 0) return;
      threads.value[index] = e.thread;
    })
    .listen(".thread.deleted", (e: any) => {
      threads.value = threads.value.filter(
        (thread) => thread.id !== e.thread_id
      );
    });
});

onUnmounted(() => {
  $echo
    .private(`channel.${props.channelId}`)
    .stopListening(".thread.created")
    .stopListening(".thread.updated")
    .stopListening(".thread.deleted");
});
</script>

<template>
  <div class="h-full bg-gray-50 shadow-inner">
    <TransitionGroup
      ref="el"
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
      <template v-if="pending">
        <li :key="`skeleton-${i}`" v-for="i in 1">
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
      </template>
    </TransitionGroup>
  </div>
</template>
