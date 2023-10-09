<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import { User, UserUpdateRequest } from "~/types/api/user";

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
          body: $props.user.body,
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
      </FormKit>
    </div>
  </div>
</template>
