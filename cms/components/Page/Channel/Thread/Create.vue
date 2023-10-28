<script setup lang="ts">
import { reset } from "@formkit/core";
import { createInput } from "@formkit/vue";
import BaseEditor from "~/components/Base/Editor/Editor.vue";
import type { ThreadStoreRequest } from "~/types/api/thread";

interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();
const emit = defineEmits<{
  submit: [];
}>();

const baseEditor = createInput(BaseEditor);
const threadStore = useThreadStore();

async function submit(body: ThreadStoreRequest) {
  await threadStore.store(props.channelId, { ...body });
  reset("thread-create-form");
}
</script>

<template>
  <div class="p-1 pt-0">
    <FormKit
      type="form"
      id="thread-create-form"
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
