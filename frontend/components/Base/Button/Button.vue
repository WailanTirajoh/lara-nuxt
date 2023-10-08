<script setup lang="ts">
export interface IButtonProps {
  size?: "lg" | "base" | "sm";
  variant?: "primary" | "secondary" | "light" | "none" | "danger";
}

const props = withDefaults(defineProps<IButtonProps>(), {
  size: "base",
  variant: "primary",
});

const variantClass = computed(() => {
  return {
    danger: "bg-red-500 text-white outline-none focus:ring focus:ring-red-300",
    primary:
      "bg-blue-500 text-white outline-none focus:ring focus:ring-violet-300",
    light: "hover:bg-gray-300  outline-none focus:ring focus:ring-gray-300",
    secondary: "",
    none: "",
  }[props.variant];
});
</script>

<template>
  <component
    is="button"
    class="p-2 rounded duration-300 text-sm disabled:cursor-not-allowed"
    :class="[variantClass]"
  >
    <slot />
  </component>
</template>
