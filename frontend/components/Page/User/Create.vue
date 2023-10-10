<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import { UserStoreRequest } from "~/types/api/user";
import { createInput } from "@formkit/vue";
import BaseSelectMulti from "~/components/Base/Select/Multi.vue";

const emit = defineEmits<{
  submit: [];
}>();

const userStore = useUserStore();

async function submit(body: UserStoreRequest, node: FormKitNode) {
  const { data, error } = await userStore.store(body);

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
      <FormKit type="form" @submit="submit">
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="Wailan Tirajoh"
          validation="required"
        />
        <FormKit
          type="email"
          name="email"
          id="email"
          label="Email"
          placeholder="wailantirajoh@gmail.com"
          validation="required|email"
        />
        <FormKit
          type="password"
          name="password"
          id="password"
          label="Password"
          placeholder="**********"
          validation="required"
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
