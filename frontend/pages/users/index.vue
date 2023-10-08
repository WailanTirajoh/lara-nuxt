<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";
import type { User } from "@/types/api/user";

useSeoMeta({
  title: "User Management",
});

definePageMeta({
  middleware: ["auth"],
});

const dialog = useDialog();
const userStore = useUserStore();

const selectedUser = ref<User | null>(null);
const isOpenOffcanvas = ref(false);

const datatable = reactive<IDatatableProps<keyof User | "no" | "action">>({
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
      field: "name",
      label: "Name",
      advanceInput: false,
      sortable: true,
    },
    {
      field: "email",
      label: "Email",
      advanceInput: false,
      sortable: true,
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
    query: query.value,
    order_by: datatable.orderBy,
    order_type: datatable.orderType,
  };
});
const { data, execute: fetchUser, pending } = await userStore.get(params);

const destroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description:
      "This action will permanently delete all the posts associated with this user",
  });
  if (!accepted) return;
  await userStore.destroy(id);
  fetchUser();
};

function add() {
  selectedUser.value = null;
  isOpenOffcanvas.value = !isOpenOffcanvas.value;
}

function edit(user: User) {
  selectedUser.value = user;
  isOpenOffcanvas.value = true;
}

function onUserCreated() {
  isOpenOffcanvas.value = false;
  fetchUser();
}

function onUserUpdated() {
  isOpenOffcanvas.value = false;
  fetchUser();
}
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Users</h1>
    </div>
    <div class="col-span-12">
      <hr>
    </div>
    <BaseOffcanvas v-model:is-open="isOpenOffcanvas" position="bottom">
      <template #headerTitle>
        {{ selectedUser ? "Edit User" : "Create New User" }}
      </template>
      <div class="p-2 overflow-auto">
        <div class="">
          <PageUserEdit
            v-if="selectedUser"
            :user="selectedUser"
            @submit="onUserUpdated"
          />
          <PageUserCreate v-else @submit="onUserCreated" />
        </div>
      </div>
    </BaseOffcanvas>
    <div class="col-span-12">
      <BaseDatatable
        v-bind="datatable"
        v-model:limit="datatable.limit"
        v-model:page="datatable.page"
        v-model:orderBy="datatable.orderBy"
        v-model:orderType="datatable.orderType"
        :data="data?.data.users ?? []"
        :loading="pending"
      >
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              v-model="datatable.search"
              type="text"
              class="p-2 rounded bg-white border w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by name, or email"
            />
            <BaseButton class="w-24" @click="add"> Add User </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto hover:bg-gray-200"
              @click="fetchUser"
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
            <div class="flex gap-1 justify-center items-center">
              <BaseButton @click="edit(data as User)"> Edit </BaseButton>
              <BaseButton variant="danger" @click="destroy(data.id)">
                Delete
              </BaseButton>
            </div>
          </template>
        </template>
      </BaseDatatable>
    </div>
  </div>
</template>
