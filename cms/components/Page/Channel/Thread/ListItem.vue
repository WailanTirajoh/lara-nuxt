<script setup lang="ts">
import { storeToRefs } from "pinia";
import type { Thread } from "~/types/api/thread";

interface PageChannelThreadItemListProps {
  channelId: string;
  thread: Thread;
}
const props = defineProps<PageChannelThreadItemListProps>();

const isEditting = ref(false);

const threadStore = useThreadStore();
const dialog = useDialog();
const authStore = useAuthStore();
const route = useRoute();

const { selectedThread, threads } = storeToRefs(threadStore);
const { profile } = storeToRefs(authStore);

const destroy = async () => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;

  await threadStore.destroy(props.channelId, props.thread.id);
  await threadStore.fetchThreads(props.channelId);
};

const select = (thread: Thread) => {
  threadStore.chooseThread(thread);
};

const { $echo } = useNuxtApp();
onMounted(() => {
  if (route.query.thread === props.thread.id.toString()) {
    threadStore.chooseThread(props.thread);
  }

  $echo
    .private(`thread.${props.thread.id.toString()}`)
    .listen(".updated", (e: any) => {
      const index = threads.value.findIndex((t) => t.id === e.thread.id);
      threads.value[index] = e.thread;
    });
});
onUnmounted(() => {
  $echo
    .private(`thread.${props.thread.id.toString()}`)
    .stopListening(".updated");
});
</script>
<template>
  <div class="relative flex gap-2 group overflow-hidden">
    <div
      class="translate-x-10 group-hover:translate-x-3 py-1 px-2 rounded duration-300 flex flex-col gap-1 justify-center items-center absolute top-2 right-2 bg-slate-700 text-slate-200 z-10"
    >
      <button class="" @click="select(thread)">
        <Icon name="ic:baseline-remove-red-eye" />
      </button>
      <template v-if="profile.id === props.thread.user.id">
        <button class="" @click="isEditting = !isEditting">
          <Icon
            :name="
              isEditting
                ? 'material-symbols:edit-off-outline'
                : 'material-symbols:edit-outline'
            "
          />
        </button>
        <button class="" @click="destroy">
          <Icon name="material-symbols:delete-forever-outline" />
        </button>
      </template>
    </div>
    <img
      :src="thread.user.profile_picture"
      alt=""
      class="w-8 h-8 rounded object-cover object-center"
    />
    <div
      class="bg-white shadow max-w-full w-full p-2 rounded duration-300"
      :class="{
        'border-l-2 border-l-slate-700': selectedThread?.id === props.thread.id,
      }"
    >
      <div class="text-lg">
        {{ thread.user.name }}
        <span class="text-base text-slate-600">
          - {{ formatDateDistance(thread.created_at) }}
        </span>
      </div>

      <div class="prose prose-sm max-w-full">
        <PageChannelThreadEdit
          v-if="isEditting"
          :thread="thread"
          :channel-id="props.channelId"
          @submit="isEditting = false"
        />
        <div v-else class="w-full" v-html="thread.body"></div>
      </div>
      <div class="text-right text-sm">{{ thread.replies_count }} replies</div>
    </div>
  </div>
</template>
