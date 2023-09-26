<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";

interface Data {
  id: number;
  title: string;
  created_by: string;
}
const datatable = reactive<IDatatableProps<keyof Data | "no" | "action">>({
  fieldKey: "id",
  columns: [
    {
      field: "no",
      label: "No",
      class: "w-14 text-center",
      advanceInput: false,
    },
    {
      field: "title",
      label: "Title",
      advanceInput: true,
    },
    {
      field: "created_by",
      label: "Created By",
      advanceInput: true,
    },
    {
      label: "Action",
      field: "action",
      advanceInput: false,
    },
  ],
  data: [
    {
      id: 1,
      title: "test",
      created_by: "Wailan",
    },
    {
      id: 2,
      title: "test",
      created_by: "Wailan",
    },
    {
      id: 3,
      title: "test",
      created_by: "Wailan",
    },
  ],
  page: 1,
  limit: 10,
});

const isOpen = ref(false);
const dialog = useDialog();

const remove = async (id: number) => {
  const res = await dialog.fire({
    title: "Are you sure?",
    description: "This action is ireversible!",
  });

  console.log(res);
};
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Posts</h1>
    </div>
    <BaseOffcanvas v-model:is-open="isOpen" position="bottom">
      <template #headerTitle> Create New Post </template>
      <div class="p-2">
        <div class="">test</div>
      </div>
    </BaseOffcanvas>
    <div class="col-span-12">
      <BaseDatatable v-bind="datatable">
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              type="text"
              class="p-2 rounded bg-gray-200 w-full focus:bg-white focus:outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title, or created by"
            />
            <BaseButton class="w-24" @click="isOpen = !isOpen">
              Add Post
            </BaseButton>
            <BaseButton variant="none" class="p-0 text-2xl">
              <Icon name="ei:refresh" />
            </BaseButton>
          </div>
        </template>
        <template #row="{ column, index, data }">
          <template v-if="column.field === 'no'">
            {{ (datatable.page - 1) * datatable.limit + index + 1 }}
          </template>
          <template v-else-if="column.field === 'action'">
            <div class="flex gap-1">
              <BaseButton> Edit </BaseButton>
              <BaseButton variant="danger" @click="remove(data.id)">
                Delete
              </BaseButton>
            </div>
          </template>
        </template>
      </BaseDatatable>
    </div>
  </div>
</template>
