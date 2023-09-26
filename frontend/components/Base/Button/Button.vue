<script setup lang="ts">
export interface IButtonProps {
  size?: "lg" | "base" | "sm";
  variant?: "primary" | "secondary" | "light" | "none" | "danger";
}

withDefaults(defineProps<IButtonProps>(), {
  size: "base",
  variant: "primary",
});
</script>

<template>
  <component
    is="button"
    class="p-2 rounded duration-300 text-sm"
    :class="{
      'bg-red-500 text-white outline-none focus:ring focus:ring-red-300': variant === 'danger',
      'bg-blue-500 text-white outline-none focus:ring focus:ring-violet-300': variant === 'primary',
      'hover:bg-gray-300  outline-none focus:ring focus:ring-gray-300': variant === 'light',
    }"
  >
    <slot />
  </component>
</template>
