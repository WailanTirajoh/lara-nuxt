<script setup lang="ts">
import { storeToRefs } from "pinia";
interface ChannelThreadReplyListProps {
  threadId: string;
}
const props = defineProps<ChannelThreadReplyListProps>();

const threadReplyStore = useThreadReplyStore();
const { replies } = storeToRefs(threadReplyStore);
onMounted(async () => {
  threadReplyStore.fetchThreadReplies(props.threadId);
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
