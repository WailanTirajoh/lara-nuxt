<script setup lang="ts">
const newTag = ref("");
const tags = defineModel<Array<string>>({
  required: true,
  default: [],
});

const createNewTag = () => {
  if (tags.value.includes(newTag.value)) return;
  tags.value = [...tags.value, newTag.value];
  newTag.value = "";
};

const removeTag = (tag: string) => {
  tags.value = tags.value.filter((t) => t !== tag);
};
</script>

<template>
  <div class="flex flex-wrap gap-2 border border-gray-400 rounded-md p-2">
    <div v-for="(tag, index) in tags">
      <div
        class="bg-gray-100 p-1 rounded text-xs flex gap-2 items-center justify-between"
      >
        <span>
          {{ tag }}
        </span>
        <button
          type="button"
          class="flex items-center justify-center text-slate-600 hover:text-slate-800"
          @click="removeTag(tag)"
        >
          <Icon name="fa6-solid:xmark" />
        </button>
      </div>
    </div>
    <input
      v-model="newTag"
      type="text"
      placeholder="Enter a tag"
      class="w-40 outline-none text-xs"
      @keydown.prevent.enter="createNewTag"
    />
  </div>
</template>
