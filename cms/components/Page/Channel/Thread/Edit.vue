<script setup lang="ts">
import { createInput } from "@formkit/vue";
import BaseEditor from "~/components/Base/Editor/Editor.vue";
import type { Thread, ThreadUpdateRequest } from "~/types/api/thread";

interface ChannelThreadCreateProps {
  channelId: string;
  thread: Thread;
}
const props = defineProps<ChannelThreadCreateProps>();
const emit = defineEmits<{
  submit: [];
}>();

const form = ref({
  body: props.thread.body,
});

const baseEditor = createInput(BaseEditor);
const threadStore = useThreadStore();

async function submit(body: ThreadUpdateRequest) {
  await threadStore.update(props.channelId, props.thread.id.toString(), {
    ...body,
  });
  emit("submit");
}
</script>

<template>
  <FormKit
    type="form"
    id="thread-update-form"
    :actions="false"
    :wrapper-class="{
      'p-1 pt-0': true,
    }"
    :value="{
      body: props.thread.body,
    }"
    @submit="submit"
  >
    <FormKit :type="baseEditor" id="body" name="body" validation="required" />
    <FormKit
      type="submit"
      label="Save"
      :wrapper-class="{
        'flex justify-end text-xs': true,
      }"
    />
  </FormKit>
</template>
