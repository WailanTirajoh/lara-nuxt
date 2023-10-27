<script setup lang="ts">
import type { Thread } from "~/types/api/thread";

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

const threadStore = useThreadStore();

const onSubmit = async () => {
  await threadStore.update(props.channelId, props.thread.id.toString(), {
    ...form.value,
  });
  emit("submit");
};
</script>

<template>
  <div class="grid grid-cols-12 gap-2 p-1">
    <div class="col-span-12">
      <BaseEditorModel v-model="form.body" />
    </div>
    <div class="col-span-12 flex justify-end">
      <BaseButton @click="onSubmit"> Save </BaseButton>
    </div>
  </div>
</template>
