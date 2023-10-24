<script setup lang="ts">
import { storeToRefs } from "pinia";

interface ChannelThreadReplyCreateProps {
  threadId: string;
}
const props = defineProps<ChannelThreadReplyCreateProps>();
const emit = defineEmits<{
  submit: [];
}>();

const form = ref({
  body: "",
});

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

const threadReplyStore = useThreadReplyStore();

const onSubmit = async () => {
  await threadReplyStore.store(props.threadId, { ...form.value });
  form.value.body = "";
};
</script>

<template>
  <div class="grid grid-cols-12 gap-2 p-1 pt-0">
    <div class="col-span-12">
      <BaseEditorModel v-model="form.body" />
    </div>
    <div class="col-span-12 flex justify-end">
      <BaseButton @click="onSubmit"> Send </BaseButton>
    </div>
  </div>
</template>
