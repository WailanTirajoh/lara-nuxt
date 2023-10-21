<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import type { RoleStoreRequest } from "~/types/api/role";
import { createInput } from "@formkit/vue";
import BaseSelectMulti from "~/components/Base/Select/Multi.vue";

const baseSelectMulti = createInput(BaseSelectMulti, {
  props: ["toptions"],
});

const emit = defineEmits<{
  submit: [];
}>();

const roleStore = useRoleStore();
const permissionStore = usePermissionStore();

async function submit(body: RoleStoreRequest, node: FormKitNode) {
  const { data, error } = await roleStore.store(body);

  if (error.value?.data) {
    node.setErrors(error.value.data.errors);
  }

  if (data.value) {
    emit("submit");
  }
}

const permissions = ref<
  Array<{
    label: string;
    value: string;
  }>
>([]);
onMounted(async () => {
  const { data } = await permissionStore.get();
  permissions.value = data.permissions.map((permission) => {
    return {
      label: permission.name,
      value: permission.name,
    };
  });
});
</script>

<template>
  <div class="grid grid-cols-12">
    <div class="col-span-12">
      <FormKit type="form" @submit="submit">
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="super"
          validation="required"
        />
        <FormKit
          :type="baseSelectMulti"
          label="Permissions"
          name="permissions"
          help="Select your permissions."
          :toptions="permissions"
        />
      </FormKit>
    </div>
  </div>
</template>
