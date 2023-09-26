<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";
import type { PostResponse, PostDeleteResponse, Post } from "@/types/api/post";

useSeoMeta({
  title: "Post Management",
});

definePageMeta({
  middleware: ["auth"],
});

const datatable = reactive<IDatatableProps<keyof Post | "no" | "action">>({
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
      advanceInput: false,
    },
    {
      field: "created_at",
      label: "Created At",
      advanceInput: false,
    },
    {
      field: "author.name" as keyof Post,
      label: "Created By",
      advanceInput: false,
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
  limit: 5,
  loading: false,
  withTrashed: false,
  search: "",
});

const isOpen = ref(false);
const dialog = useDialog();

const remove = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description: "This action is ireversible!",
  });

  if (!accepted) return;

  await $useFetchAPI<ApiResponse<PostDeleteResponse>>(`/posts/${id}`, {
    method: "delete",
  });

  execute();
};

const query = ref("");
const params = computed(() => {
  return {
    limit: datatable.limit,
    page: datatable.page,
    with_trashed: datatable.withTrashed,
    query: query.value,
  };
});

const debouncedFn = useDebounceFn(() => {
  query.value = datatable.search ?? "";
}, 500);

watch(() => datatable.search, debouncedFn);

const { data, execute, pending } = await useFetchAPI<ApiResponse<PostResponse>>(
  "/posts",
  {
    params,
  }
);

function onPostCreated() {
  execute();
  isOpen.value = false;
}
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Posts</h1>
    </div>
    <BaseOffcanvas v-model:is-open="isOpen" position="bottom">
      <template #headerTitle> Create New Post </template>
      <div class="p-2 overflow-auto">
        <div class="">
          <PagePostCreate @submit="onPostCreated" />
        </div>
      </div>
    </BaseOffcanvas>
    <div class="col-span-12">
      <BaseDatatable
        v-bind="datatable"
        :data="data?.data.posts ?? []"
        :loading="pending"
      >
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              v-model="datatable.search"
              type="text"
              class="p-2 rounded bg-gray-200 w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title, or created by"
            />
            <BaseButton class="w-24" @click="isOpen = !isOpen">
              Add Post
            </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto"
              :class="{
                'bg-gray-200 border border-slate-300': datatable.withTrashed,
              }"
              @click="datatable.withTrashed = !datatable.withTrashed"
            >
              <Icon name="material-symbols:auto-delete" />
            </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto hover:bg-gray-200"
              @click="execute"
            >
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
