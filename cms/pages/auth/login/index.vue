<script setup lang="ts">
import { storeToRefs } from "pinia";

import type { FormKitNode } from "@formkit/core";
import type { LoginRequest } from "@/types/api/auth";

useSeoMeta({
  title: "Login",
});

definePageMeta({
  layout: "guest",
});

const config = useRuntimeConfig();
const authStore = useAuthStore();
const { accessToken, isAuthenticated } = storeToRefs(authStore);

async function submit(loginRequest: LoginRequest, node: FormKitNode) {
  const { data, error } = await authStore.login(loginRequest);

  if (error.value?.data?.data.errors) {
    node.setErrors(error.value.data.data.errors);
  }

  if (!data.value) return;

  accessToken.value = data.value.data.access_token;

  window.location.href = "/";
}
</script>

<template>
  <div class="p-4 border rounded max-w-lg mx-auto bg-white shadow">
    <div class="grid grid-cols-12 gap-2">
      <div class="col-span-12">
        <FormKit type="form" submit-label="Login" @submit="submit">
          <FormKit
            type="text"
            name="email"
            id="email"
            label="Email"
            help="Your email"
            placeholder="jondoe@email.com"
            validation="required|email"
          />
          <FormKit
            type="password"
            name="password"
            id="password"
            label="Password"
            help="Your Password"
            placeholder="********"
            validation="required"
          />
        </FormKit>
      </div>
    </div>
    <div class="col-span-12">
      <hr />
    </div>
    <div class="col-span-12">
      <NuxtLink
        :to="`${config.public.wsBaseUrl}/sign-in/github`"
        :external="true"
      >
        <BaseButton class="w-full"> Login with Github </BaseButton>
      </NuxtLink>
    </div>
  </div>
</template>
