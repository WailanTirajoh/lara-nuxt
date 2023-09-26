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
      'bg-red-500 text-white': variant === 'danger',
      'bg-blue-500 text-white': variant === 'primary',
      'hover:bg-gray-300': variant === 'light',
    }"
  >
    <slot />
  </component>
</template>
