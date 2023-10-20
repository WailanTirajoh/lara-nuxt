<script setup lang="ts">
import { storeToRefs } from "pinia";
interface ChannelThreadReplyListProps {
  threadId: string;
}
const props = defineProps<ChannelThreadReplyListProps>();

const threadReplyStore = useThreadReplyStore();
const { replies } = storeToRefs(threadReplyStore);

const { $echo } = useNuxtApp();
onMounted(() => {
  threadReplyStore.fetchThreadReplies(props.threadId);

  $echo.private(`thread.${props.threadId}`).listen(".replied", (e: any) => {
    replies.value = [...replies.value, e.reply];
  });
});
onUnmounted(() => {
  $echo.private(`thread.${props.threadId}`).stopListening(".replied");
});
</script>
<template>
  <div class="p-2 h-full">
    <ul class="flex flex-col gap-2">
      <li v-for="reply in replies" :key="reply.id">
        <div>
          <div class="font-medium">
            {{ reply.user.name }}
          </div>
          <div class="prose prose-sm" v-html="reply.body"></div>
        </div>
      </li>
    </ul>
  </div>
</template>
