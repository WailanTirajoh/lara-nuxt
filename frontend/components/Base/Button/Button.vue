<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    size: "lg" | "base" | "sm";
    variant: "primary" | "secondary" | "light";
  }>(),
  {
    size: "base",
    variant: "primary",
  }
);
</script>

<template>
  <component
    is="button"
    class="p-2 rounded duration-300 text-sm"
    :class="{
      'bg-blue-500 text-white': variant === 'primary',
      'hover:bg-gray-300': variant === 'light',
    }"
  >
    <slot />
  </component>
</template>
