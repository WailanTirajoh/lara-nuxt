<script setup lang="ts">
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";

const modelValue = defineModel<string>();

const editor = useEditor({
  content: modelValue.value,
  extensions: [StarterKit],
  editorProps: {
    attributes: {
      class:
        "prose lg:prose-xl p-2 w-full max-w-full p-2 bg-white w-full focus:bg-white outline-none duration-300",
    },
  },
  onUpdate: () => {
    modelValue.value = editor.value?.getHTML();
  },
});

watch(modelValue, (value) => {
  if (!value) {
    editor.value?.commands.setContent("");
  }
});
</script>

<template>
  <div v-if="editor" class="border border-gray-400 rounded-md overflow-hidden">
    <EditorContent :editor="editor" />
    <BaseEditorToolbar :editor="editor" />
  </div>
</template>
