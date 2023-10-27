<script setup lang="ts">
import type { FormKitFrameworkContext } from "@formkit/core";

export interface Props {
  context: FormKitFrameworkContext;
}
const props = defineProps<Props>();

const permissions = computed({
  get() {
    return props.context.value ?? [];
  },
  set(value) {
    props.context.node.input(value);
  },
});

const items = computed<
  Array<{
    label: string;
    value: any;
  }>
>(
  () =>
    props.context.toptions as Array<{
      label: string;
      value: any;
    }>
);
</script>

<template>
  <div>
    <table class="w-full" aria-describedby="mydesc">
      <thead>
        <tr class="bg-slate-100">
          <th class="border p-1">No</th>
          <th class="border p-1">View</th>
          <th class="border p-1">Action</th>
          <th class="border p-1">Granted</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="!items">
          <tr>
            <td colspan="4">Loading...</td>
          </tr>
        </template>
        <template v-else-if="items.length === 0">
          <tr>
            <td colspan="4" class="border p-1 text-center">No Data</td>
          </tr>
        </template>
        <template v-else>
          <tr v-for="(item, index) in items">
            <td class="border p-1 text-center">
              {{ index + 1 }}
            </td>
            <td class="border p-1">
              {{ item.label.split("-")[0] }}
            </td>
            <td class="border p-1">
              {{ item.label.split("-")[1] }}
            </td>
            <td class="border p-1">
              <input
                type="checkbox"
                v-model="permissions"
                :value="item.value"
              />
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>
