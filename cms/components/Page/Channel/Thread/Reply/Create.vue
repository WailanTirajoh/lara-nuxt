<script setup lang="ts">
import { reset } from "@formkit/core";
import { createInput } from "@formkit/vue";
import { storeToRefs } from "pinia";
import BaseEditor from "~/components/Base/Editor/Editor.vue";
import type { ThreadReplyStoreRequest } from "~/types/api/thread-reply";

interface ChannelThreadReplyCreateProps {
  threadId: string;
}
const props = defineProps<ChannelThreadReplyCreateProps>();
const emit = defineEmits<{
  submit: [];
}>();

const baseEditor = createInput(BaseEditor);
const form = ref({
  body: "",
});

const threadReplyStore = useThreadReplyStore();

async function submit(body: ThreadReplyStoreRequest) {
  await threadReplyStore.store(props.threadId, { ...body });
  reset("reply-create-form");
}

const authStore = useAuthStore();
const { profile } = storeToRefs(authStore);
const { $echo } = useNuxtApp();

watchThrottled(
  () => form.value.body,
  () => {
    $echo
      .join(`thread-presence.${props.threadId}`)
      .whisper("typing", profile.value);
  },
  { throttle: 3000 }
);
</script>

<template>
  <div class="p-1 pt-0">
    <FormKit
      type="form"
      id="reply-create-form"
      :actions="false"
      :wrapper-class="{
        'p-1 pt-0': true,
      }"
      @submit="submit"
    >
      <FormKit
        :type="baseEditor"
        name="body"
        id="body"
        v-model="form.body"
        placeholder="My great post"
        validation="required"
      />
      <FormKit
        type="submit"
        label="Send"
        :wrapper-class="{
          'flex justify-end': true,
        }"
      />
    </FormKit>
  </div>
</template>
