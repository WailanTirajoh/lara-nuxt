<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import type { Role, RoleUpdateRequest } from "~/types/api/role";
import BaseInputPermission from "~/components/Base/InputPermission/InputPermission.vue";
import { createInput } from "@formkit/vue";

const baseInputPermission = createInput(BaseInputPermission, {
  props: ["toptions"],
});

interface IRoleEditInterface {
  role: Role;
}
const props = defineProps<IRoleEditInterface>();
const emit = defineEmits<{
  submit: [];
}>();

const roleStore = useRoleStore();
const permissionStore = usePermissionStore();

async function submit(body: RoleUpdateRequest, node: FormKitNode) {
  const { data, error } = await roleStore.update(props.role.id, body);

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
      <FormKit
        type="form"
        @submit="submit"
        :value="{
          ...$props.role,
        }"
      >
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="Wailan Tirajoh"
          validation="required"
          :disabled="role.name === 'Super'"
        />
        <FormKit
          :type="baseInputPermission"
          label="Permissions"
          name="permissions"
          help="Select your permissions."
          :toptions="permissions"
        />
      </FormKit>
    </div>
  </div>
</template>
