<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";
import type { Post } from "@/types/api/post";

useSeoMeta({
  title: "Post Management",
});

definePageMeta({
  middleware: ["auth"],
  permissions: ["post-viewAny"],
});

const dialog = useDialog();
const postStore = usePostStore();

const selectedPost = ref<Post | null>(null);
const isOpenModal = ref(false);

const datatable = reactive<IDatatableProps<keyof Post | "no" | "action">>({
  fieldKey: "id",
  columns: [
    {
      label: "Action",
      field: "action",
      class: "w-32 bg-gray-50",
      advanceInput: false,
    },
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
      sortable: true,
    },
    {
      field: "tags",
      label: "Tags",
      advanceInput: false,
      sortable: false,
    },
    {
      field: "created_at",
      format: "datetime",
      label: "Created At",
      advanceInput: false,
      sortable: true,
    },
    {
      field: "updated_at",
      format: "datetime",
      label: "Updated At",
      advanceInput: false,
      sortable: true,
    },
    {
      field: "author.name" as keyof Post,
      label: "Author",
      advanceInput: false,
    },
  ],
  data: [],
  page: 1,
  limit: 5,
  orderBy: "id",
  orderType: "asc",
  loading: false,
  trashed: false,
  search: "",
});

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
    order_by: datatable.orderBy,
    order_type: datatable.orderType,
  };
});
const { data, execute: fetchPost, pending } = await postStore.get(params);

const permanentDestroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description: "This action is ireversible!",
  });
  if (!accepted) return;
  await postStore.permanentDestroy(id);
  fetchPost();
};

const destroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;
  await postStore.destroy(id);
  fetchPost();
};

const restore = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
  });
  if (!accepted) return;
  await postStore.restore(id);
  fetchPost();
};

function add() {
  selectedPost.value = null;
  isOpenModal.value = !isOpenModal.value;
}

function edit(post: Post) {
  selectedPost.value = post;
  isOpenModal.value = true;
}

function onPostCreated() {
  isOpenModal.value = false;
  fetchPost();
}

function onPostUpdated() {
  isOpenModal.value = false;
  fetchPost();
}
</script>

<template>
  <div class="p-4 sm:p-8 grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Posts</h1>
    </div>
    <div class="col-span-12">
      <hr />
    </div>
    <BaseModal v-model="isOpenModal" position="bottom" backdrop="static">
      <template #title>
        {{ selectedPost ? "Edit Post" : "Create New Post" }}
      </template>
      <div class="p-2 overflow-auto h-full">
        <div class="">
          <PagePostEdit
            v-if="selectedPost"
            :post="selectedPost"
            @submit="onPostUpdated"
          />
          <PagePostCreate v-else @submit="onPostCreated" />
        </div>
      </div>
    </BaseModal>
    <div class="col-span-12">
      <BaseDatatable
        v-bind="datatable"
        v-model:limit="datatable.limit"
        v-model:page="datatable.page"
        v-model:orderBy="datatable.orderBy"
        v-model:orderType="datatable.orderType"
        :data="data?.data.posts ?? []"
        :loading="pending"
      >
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              v-model="datatable.search"
              type="text"
              class="p-2 rounded bg-white border w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title, or created by"
            />
            <BaseButton
              v-if="hasPermissions('post-create')"
              class="w-24"
              @click="add"
            >
              Add Post
            </BaseButton>
            <BaseButton
              v-if="hasPermissions('post-forceDelete', 'post-restore')"
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
          <template v-else-if="column.field === 'tags'">
            <div class="flex flex-wrap gap-1">
              <div
                class="bg-slate-100 p-1 rounded"
                v-for="tag in data[column.field]"
              >
                {{ tag }}
              </div>
            </div>
          </template>
          <template v-else-if="column.field === 'action'">
            <template v-if="datatable.trashed">
              <div class="flex gap-1 justify-center items-center">
                <BaseButton
                  v-if="hasPermissions('post-restore')"
                  variant="primary"
                  @click="restore(data.id)"
                >
                  Restore
                </BaseButton>
                <BaseButton
                  v-if="hasPermissions('post-forceDelete')"
                  variant="danger"
                  @click="permanentDestroy(data.id)"
                >
                  Remove
                </BaseButton>
              </div>
            </template>
            <template v-else>
              <div class="flex gap-1 justify-center items-center">
                <BaseButton
                  v-if="hasPermissions('post-update')"
                  @click="edit(data as Post)"
                >
                  Edit
                </BaseButton>
                <BaseButton
                  v-if="hasPermissions('post-delete')"
                  variant="danger"
                  @click="destroy(data.id)"
                >
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
