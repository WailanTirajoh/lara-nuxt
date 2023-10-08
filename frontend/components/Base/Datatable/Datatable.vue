<script setup lang="ts" generic="T extends string">
export interface IDatatableProps<T extends string> {
  columns: Array<{
    field: T;
    label: string;
    headClass?: string;
    class?: string;
    advanceInput?: boolean;
    format?: "text" | "datetime" | "date";
    sortable?: boolean;
  }>;
  data: Array<Record<T | string, any>>;
  fieldKey: T;
  page: number;
  limit: number;
  loading: boolean;
  trashed?: boolean;
  search?: string;
  totalData?: number;
  orderBy: string;
  orderType: "asc" | "desc";
}

type FormatType = "text" | "datetime" | "date";

withDefaults(defineProps<IDatatableProps<T>>(), {
  loading: false,
});

const format = (value: string, type: FormatType = "text") => {
  let result = value;
  switch (type) {
    case "datetime":
      result = formatDatetime(value);
      break;
    case "date":
      result = formatDate(value);
      break;
  }
  return result;
};

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

const limit = defineModel<number>("limit");
const page = defineModel<number>("page");
const orderBy = defineModel<string>("orderBy");
const orderType = defineModel<"asc" | "desc">("orderType");

const perPageOptions = ref([
  {
    label: 5,
    value: 5,
  },
  {
    label: 10,
    value: 10,
  },
  {
    label: 25,
    value: 25,
  },
  {
    label: 50,
    value: 50,
  },
  {
    label: 100,
    value: 100,
  },
]);

function order(field: string) {
  orderBy.value = field;
  orderType.value = orderType.value === "asc" ? "desc" : "asc";
}
</script>

<template>
  <div class="overflow-auto w-full border rounded relative">
    <div class="bg-gray-50">
      <slot name="head"> </slot>
    </div>
    <div class="overflow-auto">
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
              :class="[
                {
                  asc: orderBy == column.field && orderType == 'asc',
                  desc: orderBy == column.field && orderType == 'desc',
                  sorting: column.sortable,
                },
                column.headClass,
              ]"
              @click="column.sortable ? order(column.field) : ''"
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
          <template v-if="data.length === 0">
            <tr class="duration-100 rounded-lg hover:bg-gray-100">
              <td
                class="p-3 border first:border-l-0 last:border-r-0 hover:bg-gray-200 duration-100 text-center"
                :colspan="columns.length"
              >
                <slot name="empty">No data</slot>
              </td>
            </tr>
            <slot name="empty"></slot>
          </template>
          <template v-else>
            <tr
              v-for="(d, index) in data"
              :key="d[fieldKey as T]"
              class="duration-100 rounded-lg hover:bg-gray-100"
            >
              <td
                v-for="column in columns"
                class="p-3 border first:border-l-0 last:border-r-0 hover:bg-gray-200 duration-100"
                :class="[column.class]"
              >
                <slot name="row" :data="d" :column="column" :index="index">
                  {{ format(getFieldValue(d, column.field), column.format) }}
                </slot>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div class="p-2">
      <div class="flex items-center justify-between">
        <div class="">
          <select
            id="limit"
            v-model="limit"
            name="limit"
            class="border p-2 rounded bg-gray-white w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
          >
            <option
              v-for="pageOption in perPageOptions"
              :value="pageOption.value"
            >
              {{ pageOption.label }}
            </option>
          </select>
        </div>
        <div class="flex items-center gap-2">
          <ul class="flex gap-2">
            <li>
              <BaseButton
                variant="light"
                @click="page--"
                :disabled="page === 1"
              >
                <Icon name="ion:chevron-back" />
              </BaseButton>
            </li>
            <li>
              <BaseButton
                variant="light"
                @click="page++"
                :disabled="data.length < limit"
              >
                <Icon name="ion:chevron-forward" />
              </BaseButton>
            </li>
          </ul>
        </div>
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
        class="w-full h-full absolute top-0 flex items-center justify-center bg-slate-800 bg-opacity-10 duration-100 cursor-wait"
      >
        <div
          class="flex flex-col gap-2 items-center justify-center bg-slate-600 text-white p-2 rounded bg-opacity-75"
        >
          <Icon name="eos-icons:loading" class="text-3xl" />
          <div class="">Please Wait</div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.sorting {
  position: relative;
  cursor: pointer;
}

.sorting::after {
  content: "\2304";
  font-size: 0.6rem;
  top: 0.75rem;
  position: absolute;
  right: 0.5rem;
  opacity: 0.35;
}

.sorting::before {
  content: "\2303";
  font-size: 0.6rem;
  top: 0.75rem;
  position: absolute;
  right: 0.5rem;
  opacity: 0.35;
}

.sorting.desc::after {
  opacity: 1;
}

.sorting.asc::before {
  opacity: 1;
}
</style>
