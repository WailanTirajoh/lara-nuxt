<script setup lang="ts">
import type { FormKitFrameworkContext } from "@formkit/core";

export interface Props {
  context: FormKitFrameworkContext;
}
const props = defineProps<Props>();

const modelValue = computed({
  get() {
    return props.context.value;
  },
  set(value: string) {
    props.context.node.input(value);
  },
});
</script>

<template>
  <BaseEditorModel v-model="modelValue" />
</template>
