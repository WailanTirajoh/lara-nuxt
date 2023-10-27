<script setup lang="ts">
interface ChannelThreadCreateProps {
  channelId: string;
}
const props = defineProps<ChannelThreadCreateProps>();
const emit = defineEmits<{
  submit: [];
}>();

const form = ref({
  body: "",
});

const threadStore = useThreadStore();

const onSubmit = async () => {
  await threadStore.store(props.channelId, { ...form.value });
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
