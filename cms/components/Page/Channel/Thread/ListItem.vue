<script setup lang="ts">
import { storeToRefs } from "pinia";
import { Thread } from "~/types/api/thead";

interface PageChannelThreadItemListProps {
  channelId: string;
  thread: Thread;
}
const props = defineProps<PageChannelThreadItemListProps>();

const isEditting = ref(false);

const threadStore = useThreadStore();
const dialog = useDialog();
const authStore = useAuthStore();
const { profile } = storeToRefs(authStore);

const destroy = async () => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;

  await threadStore.destroy(props.channelId, props.thread.id);
  await threadStore.fetchThreads(props.channelId);
};
</script>
<template>
  <div class="relative flex gap-2 group overflow-hidden">
    <div
      v-if="profile.id === props.thread.user.id"
      class="translate-x-10 group-hover:translate-x-3 py-1 px-2 rounded duration-300 flex flex-col gap-1 justify-center items-center absolute top-2 right-2 bg-slate-700 text-slate-200 z-10"
    >
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
    </div>
    <img
      :src="thread.user.profile_picture"
      alt=""
      class="w-8 h-8 rounded object-cover object-center"
    />
    <div class="prose prose-sm bg-white shadow max-w-full w-full p-2 rounded">
      <div class="text-lg">
        {{ thread.user.name }}
        <span class="text-base text-slate-600">
          - {{ formatDateDistance(thread.created_at) }}
        </span>
      </div>

      <PageChannelThreadEdit
        v-if="isEditting"
        :thread="thread"
        :channel-id="props.channelId"
        @submit="isEditting = false"
      />
      <div v-else class="w-full" v-html="thread.body"></div>
    </div>
  </div>
</template>
