<script setup lang="ts" generic="T extends string | number | symbol = string">
export interface IDatatableProps<T extends string | number | symbol = string> {
  columns: Array<{
    field: T;
    label: string;
    class?: string;
    advanceInput?: boolean;
  }>;
  data: Array<Record<T | string, any>>;
  fieldKey: T;
  page: number;
  limit: number;
}

const props = defineProps<IDatatableProps<T>>();
</script>

<template>
  <div class="overflow-auto w-full border rounded">
    <div class="bg-gray-50">
      <slot name="head"> </slot>
    </div>
    <table
      class="w-full overflow-hidden text-slate-700"
      aria-label="Posts Table"
    >
      <thead class="bg-gray-50 border-b">
        <tr>
          <th
            v-for="column in columns"
            :key="column.field"
            scope="col"
            class="text-slate-900 font-normal p-2"
            :class="[column.class]"
          >
            {{ column.label }}
          </th>
        </tr>
        <tr>
          <th
            v-for="column in columns"
            :key="column.field"
            scope="col"
            class="text-slate-900 font-normal p-1"
          >
            <input
              v-if="column.advanceInput"
              type="text"
              class="p-2 rounded bg-gray-white w-full focus:bg-white focus:outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title"
            />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(d, index) in data"
          :key="d[fieldKey as T]"
          class="duration-100 rounded-lg hover:bg-gray-100"
        >
          <td
            class="p-3 border first:border-l-0 last:border-r-0"
            v-for="column in columns"
          >
            <slot name="row" :data="d" :column="column" :index="index">
              {{ d[column.field] }}
            </slot>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="p-2">
      <div class="flex items-center justify-between">
        <div class="">Rows per page</div>
        <ul class="flex gap-2">
          <li>
            <BaseButton variant="light">
              <Icon name="ion:chevron-back" />
            </BaseButton>
          </li>
          <li>
            <BaseButton variant="light">
              <Icon name="ion:chevron-forward" />
            </BaseButton>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
