<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";
import type { Post } from "@/types/api/post";

useSeoMeta({
  title: "Post Management",
});

definePageMeta({
  middleware: ["auth"],
});

const dialog = useDialog();
const postStore = usePostStore();

const selectedPost = ref<Post | null>(null);
const isOpenOffcanvas = ref(false);

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
      format: 'datetime',
      label: "Created At",
      advanceInput: false,
    },
    {
      field: "updated_at",
      format: 'datetime',
      label: "Updated At",
      advanceInput: false,
    },
    {
      field: "author.name" as keyof Post,
      label: "Author",
      advanceInput: false,
    },
    {
      label: "Action",
      field: "action",
      advanceInput: false,
    },
  ],
  data: [],
  page: 1,
  limit: 5,
  loading: false,
  trashed: false,
  search: "",
});


const remove = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description: "This action is ireversible!",
  });

  if (!accepted) return;

  await postStore.destroy(id);

  fetchPost();
};

const restore = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description: "This action is ireversible!",
  });

  if (!accepted) return;

  await postStore.restore(id);

  fetchPost();
};

const query = ref("");
const debouncedFn = useDebounceFn(() => {
  query.value = datatable.search ?? "";
}, 500);
watch(() => datatable.search, debouncedFn);

const params = computed(() => {
  return {
    limit: datatable.limit,
    page: datatable.page,
    with_trashed: datatable.trashed,
    query: query.value,
  };
});
const { data, execute: fetchPost, pending } = await postStore.get(params);

function onPostCreated() {
  fetchPost();
  isOpenOffcanvas.value = false;
}

function add() {
  selectedPost.value = null;
  isOpenOffcanvas.value = !isOpenOffcanvas.value;
}

function edit(post: Post) {
  selectedPost.value = post;
  isOpenOffcanvas.value = true;
}
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Posts</h1>
    </div>
    <BaseOffcanvas v-model:is-open="isOpenOffcanvas" position="bottom">
      <template #headerTitle>
        {{ selectedPost ? "Edit Post" : "Create New Post" }}
      </template>
      <div class="p-2 overflow-auto">
        <div class="">
          <PagePostEdit
            v-if="selectedPost"
            :post="selectedPost"
            @submit="onPostCreated"
          />
          <PagePostCreate v-else @submit="onPostCreated" />
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
            <BaseButton class="w-24" @click="add"> Add Post </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto"
              :class="{
                'bg-gray-200 border border-slate-300': datatable.trashed,
              }"
              @click="datatable.trashed = !datatable.trashed"
            >
              <Icon name="material-symbols:auto-delete" />
            </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto hover:bg-gray-200"
              @click="fetchPost"
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
            <template v-if="datatable.trashed">
              <BaseButton variant="primary" @click="restore(data.id)">
                Restore
              </BaseButton>
            </template>
            <template v-else>
              <div class="flex gap-1">
                <BaseButton @click="edit(data as Post)"> Edit </BaseButton>
                <BaseButton variant="danger" @click="remove(data.id)">
                  Delete
                </BaseButton>
              </div>
            </template>
          </template>
        </template>
      </BaseDatatable>
    </div>
  </div>
</template>
