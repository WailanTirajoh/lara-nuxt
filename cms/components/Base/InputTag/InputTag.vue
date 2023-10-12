<script setup lang="ts">
import type { FormKitFrameworkContext } from "@formkit/core";

export interface Props {
  context: FormKitFrameworkContext;
}
const props = defineProps<Props>();

const tag = computed({
  get() {
    return props.context.value ?? [];
  },
  set(value) {
    console.log("in");
    props.context.node.input(value);
  },
});
</script>

<template>
  <div>
    <BaseInputTagBase
      :model-value="props.context.value"
      @update:model-value="(v) => props.context.node.input(v)"
    />
  </div>
</template>
