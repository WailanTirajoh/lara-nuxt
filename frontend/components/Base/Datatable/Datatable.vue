<script setup lang="ts" generic="T extends string">
export interface IDatatableProps<T extends string> {
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
  loading: boolean;
  withTrashed?: boolean;
  search?: string;
}

withDefaults(defineProps<IDatatableProps<T>>(), {
  loading: false,
});

const getFieldValue = (data: Record<string, any>, field: string) => {
  const keys = field.split(".");
  let value: any = data;

  for (const key of keys) {
    if (value[key]) {
      value = value[key];
    } else {
      value = null; // Handle missing keys gracefully if needed
      break;
    }
  }

  return value;
};
</script>

<template>
  <div class="overflow-auto w-full border rounded relative">
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
              class="p-2 rounded bg-gray-white w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
              :placeholder="`Search by ${column.label}`"
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
            class="p-3 border first:border-l-0 last:border-r-0 hover:bg-gray-200 duration-100"
            v-for="column in columns"
          >
            <slot name="row" :data="d" :column="column" :index="index">
              {{ getFieldValue(d, column.field) }}
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
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="loading"
        class="w-full h-full absolute top-0 flex items-center justify-center bg-slate-800 bg-opacity-10 duration-100"
      >
        <Icon name="eos-icons:loading" class="text-3xl text-slate-700" />
      </div>
    </Transition>
  </div>
</template>
