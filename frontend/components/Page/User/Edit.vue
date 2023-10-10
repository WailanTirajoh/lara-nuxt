<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import { User, UserUpdateRequest } from "~/types/api/user";
import { createInput } from "@formkit/vue";
import BaseSelectMulti from "~/components/Base/Select/Multi.vue";

interface IUserEditInterface {
  user: User;
}
const props = defineProps<IUserEditInterface>();
const emit = defineEmits<{
  submit: [];
}>();

const userStore = useUserStore();

async function submit(body: UserUpdateRequest, node: FormKitNode) {
  const { data, error } = await userStore.update(props.user.id, body);

  if (error.value?.data) {
    node.setErrors(error.value.data.errors);
  }

  if (data.value) {
    emit("submit");
  }
}

const baseSelectMulti = createInput(BaseSelectMulti, {
  props: ["toptions"],
});

const roleStore = useRoleStore();
const roles = ref<
  Array<{
    label: string;
    value: string;
  }>
>([]);

const params = ref({
  limit: 1000,
  page: 1,
  query: "",
});
onMounted(async () => {
  const response = await roleStore.$get(params);
  roles.value = response.data.roles.map((role) => {
    return {
      label: role.name,
      value: role.name,
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
          name: $props.user.name,
          email: $props.user.email,
          roles: $props.user.roles,
        }"
      >
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="Wailan Tirajoh"
          validation="required"
        />
        <FormKit
          type="text"
          name="email"
          id="email"
          label="Email"
          placeholder="wailantirajoh@gmail.com"
          validation="required"
          :disabled="true"
        />
        <FormKit
          :type="baseSelectMulti"
          label="Roles"
          name="roles"
          help="Select your roles."
          :toptions="roles"
        />
      </FormKit>
    </div>
  </div>
</template>
