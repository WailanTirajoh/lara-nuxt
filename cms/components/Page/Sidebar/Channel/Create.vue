<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import { ChannelStoreRequest } from "~/types/api/channel";
import { createInput } from "@formkit/vue";
import BaseEditor from "~/components/Base/Editor/Editor.vue";
import BaseInputTag from "~/components/Base/InputTag/InputTag.vue";

const baseEditor = createInput(BaseEditor);
const baseInputTag = createInput(BaseInputTag);

const emit = defineEmits<{
  submit: [];
}>();

const channelStore = useChannelStore();

async function submit(body: ChannelStoreRequest, node: FormKitNode) {
  const { data, error } = await channelStore.store(body);

  if (error.value?.data) {
    node.setErrors(error.value.data.errors);
  }

  if (data.value) {
    emit("submit");
  }
}
</script>

<template>
  <div class="grid grid-cols-12">
    <div class="col-span-12">
      <FormKit type="form" @submit="submit">
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="Iseng"
          validation="required"
        />
      </FormKit>
    </div>
  </div>
</template>
