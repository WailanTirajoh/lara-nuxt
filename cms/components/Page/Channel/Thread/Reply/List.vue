<script setup lang="ts">
import { storeToRefs } from "pinia";
interface ChannelThreadReplyListProps {
  threadId: string;
}
const props = defineProps<ChannelThreadReplyListProps>();
const emit = defineEmits<{
  "reply-fetched": [];
}>();

const threadReplyStore = useThreadReplyStore();
const { replies, isFetchingReplies } = storeToRefs(threadReplyStore);

onMounted(async () => {
  await threadReplyStore.fetchThreadReplies(props.threadId);
  emit("reply-fetched");
});
</script>
<template>
  <div class="p-2 h-full">
    <ul class="flex flex-col gap-2">
      <template v-if="isFetchingReplies">
        <li class="text-center">Fetching . . .</li>
      </template>
      <template v-else-if="replies.length === 0">
        <li class="text-center">No Replies</li>
      </template>
      <template v-else>
        <li v-for="reply in replies" :key="reply.id" class="mb-2">
          <div>
            <div class="flex gap-2 mb-1">
              <img
                :src="reply.user.profile_picture"
                alt="user-profile"
                class="w-6 h-6 rounded-full"
              />
              <div class="w-full">
                <div class="font-medium">
                  {{ reply.user.name }}
                </div>
                <div class="prose prose-sm" v-html="reply.body"></div>
              </div>
            </div>
          </div>
        </li>
      </template>
    </ul>
  </div>
</template>
