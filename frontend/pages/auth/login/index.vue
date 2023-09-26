<script setup lang="ts">
definePageMeta({
  layout: "guest",
});
</script>

<template>
  <div class="p-4 border rounded max-w-lg mx-auto bg-white shadow">
    <div class="grid grid-cols-12">
      <div class="col-span-12">
        <FormKit type="form">
          <FormKit
            type="text"
            name="email"
            id="email"
            label="Email"
            help="Your email"
            placeholder="jondoe@email.com"
          />
          <FormKit
            type="password"
            name="password"
            id="password"
            label="Password"
            help="Your Password"
            placeholder="********"
          />
        </FormKit>
      </div>
    </div>
  </div>
</template>
