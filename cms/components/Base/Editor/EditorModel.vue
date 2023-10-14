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
</script>

<template>
  <div class="border border-gray-400 rounded-md overflow-hidden">
    <editor-content :editor="editor" />
    <footer class="border-b p-1 bg-gray-200">
      <div class="flex flex-wrap gap-1" v-if="editor">
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            @click="editor.chain().focus().toggleBold().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('bold') }"
          >
            <Icon name="material-symbols:format-bold" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleItalic().run()"
            :disabled="!editor.can().chain().focus().toggleItalic().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('italic') }"
          >
            <Icon name="material-symbols:format-italic" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleStrike().run()"
            :disabled="!editor.can().chain().focus().toggleStrike().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('strike') }"
          >
            <Icon name="material-symbols:format-strikethrough" />
          </button>
        </div>

        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            @click="editor.chain().focus().setParagraph().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('paragraph') }"
          >
            <Icon name="material-symbols:format-paragraph" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 1,
              }),
            }"
          >
            <Icon name="mdi:format-header-1" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 2,
              }),
            }"
          >
            <Icon name="mdi:format-header-2" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 3,
              }),
            }"
          >
            <Icon name="mdi:format-header-3" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 4,
              }),
            }"
          >
            <Icon name="mdi:format-header-4" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 5,
              }),
            }"
          >
            <Icon name="mdi:format-header-5" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('heading', {
                level: 6,
              }),
            }"
          >
            <Icon name="mdi:format-header-6" />
          </button>
        </div>
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            @click="editor.chain().focus().toggleCode().run()"
            :disabled="!editor.can().chain().focus().toggleCode().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('code') }"
          >
            <Icon name="material-symbols:code" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleCodeBlock().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{ 'bg-slate-600 text-white': editor.isActive('codeBlock') }"
          >
            <Icon name="ion:terminal-sharp" />
          </button>
        </div>
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().unsetAllMarks().run()"
          >
            <Icon name="material-symbols:format-clear" />
          </button>

          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().clearNodes().run()"
          >
            <Icon name="carbon:reset" />
          </button>
        </div>
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            @click="editor.chain().focus().toggleBulletList().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('bulletList'),
            }"
          >
            <Icon name="material-symbols:format-list-bulleted" />
          </button>

          <button
            type="button"
            @click="editor.chain().focus().toggleOrderedList().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('orderedList'),
            }"
          >
            <Icon name="material-symbols:format-list-numbered" />
          </button>
        </div>
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            @click="editor.chain().focus().toggleBlockquote().run()"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            :class="{
              'bg-slate-600 text-white': editor.isActive('blockquote'),
            }"
          >
            <Icon name="material-symbols:format-quote" />
          </button>

          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().setHorizontalRule().run()"
          >
            <Icon name="radix-icons:divider-horizontal" />
          </button>

          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().setHardBreak().run()"
          >
            <Icon name="ic:sharp-keyboard-return" />
          </button>
        </div>
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().undo().run()"
            :disabled="!editor.can().chain().focus().undo().run()"
          >
            <Icon name="material-symbols:undo" />
          </button>

          <button
            type="button"
            class="bg-gray-100 border p-2 rounded-sm text-slate-600 w-7 h-7 flex items-center justify-center text-xs"
            @click="editor.chain().focus().redo().run()"
            :disabled="!editor.can().chain().focus().redo().run()"
          >
            <Icon name="material-symbols:redo" />
          </button>
        </div>
      </div>
    </footer>
  </div>
</template>
