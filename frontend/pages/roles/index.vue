<script setup lang="ts">
import type { IDatatableProps } from "@/components/Base/Datatable/Datatable.vue";
import type { Role } from "@/types/api/role";

useSeoMeta({
  title: "Role Management",
});

definePageMeta({
  middleware: ["auth"],
  permissions: ["role-access"],
});

const dialog = useDialog();
const roleStore = useRoleStore();

const selectedRole = ref<Role | null>(null);
const isOpenOffcanvas = ref(false);

const datatable = reactive<IDatatableProps<keyof Role | "no" | "action">>({
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
      field: "guard_name",
      label: "Guard Name",
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
const { data, execute: fetchRole, pending } = await roleStore.get(params);

const destroy = async (id: number) => {
  const accepted = await dialog.fire({
    title: "Are you sure?",
    description:
      "This action will permanently delete all the posts associated with this role",
  });
  if (!accepted) return;
  await roleStore.destroy(id);
  fetchRole();
};

function add() {
  selectedRole.value = null;
  isOpenOffcanvas.value = !isOpenOffcanvas.value;
}

function edit(role: Role) {
  selectedRole.value = role;
  isOpenOffcanvas.value = true;
}

function onRoleCreated() {
  isOpenOffcanvas.value = false;
  fetchRole();
}

function onRoleUpdated() {
  isOpenOffcanvas.value = false;
  fetchRole();
}
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Roles</h1>
    </div>
    <div class="col-span-12">
      <hr />
    </div>
    <BaseOffcanvas v-model:is-open="isOpenOffcanvas" position="right">
      <template #headerTitle>
        {{ selectedRole ? "Edit Role" : "Create New Role" }}
      </template>
      <div class="p-2 overflow-auto h-full">
        <div class="">
          <PageRoleEdit
            v-if="selectedRole"
            :role="selectedRole"
            @submit="onRoleUpdated"
          />
          <PageRoleCreate v-else @submit="onRoleCreated" />
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
        :data="data?.data.roles ?? []"
        :loading="pending"
      >
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              v-model="datatable.search"
              type="text"
              class="p-2 rounded bg-white border w-full focus:bg-white outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by name"
            />
            <BaseButton
              v-if="hasPermissions('role-store')"
              class="w-24"
              @click="add"
            >
              Add Role
            </BaseButton>
            <BaseButton
              variant="none"
              class="p-0 text-2xl text-slate-600 rounded-full w-8 h-8 flex items-center justify-center my-auto hover:bg-gray-200"
              @click="fetchRole"
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
            <div
              v-if="data.name !== 'Super'"
              class="flex gap-1 justify-center items-center"
            >
              <BaseButton
                v-if="hasPermissions('role-update')"
                @click="edit(data as Role)"
              >
                Edit
              </BaseButton>
              <BaseButton
                v-if="hasPermissions('role-delete')"
                variant="danger"
                @click="destroy(data.id)"
              >
                Delete
              </BaseButton>
            </div>
          </template>
        </template>
      </BaseDatatable>
    </div>
  </div>
</template>
