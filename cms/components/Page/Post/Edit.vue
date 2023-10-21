<script setup lang="ts">
import { getNode, type FormKitNode } from "@formkit/core";
import type { Post, PostUpdateRequest } from "~/types/api/post";
import { createInput } from "@formkit/vue";
import BaseEditor from "~/components/Base/Editor/Editor.vue";
import BaseInputTag from "~/components/Base/InputTag/InputTag.vue";

interface IPostEditInterface {
  post: Post;
}
const props = defineProps<IPostEditInterface>();
const emit = defineEmits<{
  submit: [];
}>();

const baseEditor = createInput(BaseEditor);
const baseInputTag = createInput(BaseInputTag);
const postStore = usePostStore();

async function submit(body: PostUpdateRequest, node: FormKitNode) {
  const { data, error } = await postStore.update(props.post.id, body);

  if (error.value?.data) {
    node.setErrors(error.value.data.errors);
  }

  if (data.value) {
    emit("submit");
  }
}

const titleNode = computed(() => getNode("title")!);
const slugNode = computed(() => getNode("slug")!);
onMounted(() => {
  titleNode.value.on("input", (event) => {
    slugNode.value.input(stringToSlug(event.payload));
  });
});
</script>

<template>
  <div class="grid grid-cols-12">
    <div class="col-span-12">
      <FormKit
        type="form"
        @submit="submit"
        :value="{
          ...$props.post,
        }"
      >
        <FormKit
          type="text"
          name="title"
          id="title"
          label="Title"
          placeholder="My great post"
          validation="required"
        />
        <FormKit
          type="text"
          name="slug"
          id="slug"
          label="Slug"
          placeholder="slug"
          validation="required"
          disabled="true"
        />
        <FormKit
          :type="baseEditor"
          name="body"
          id="body"
          label="Body"
          placeholder="My great post"
          validation="required"
        />
        <FormKit :type="baseInputTag" name="tags" id="tags" label="Tags" />
      </FormKit>
    </div>
  </div>
</template>
