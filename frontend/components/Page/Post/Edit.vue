<script setup lang="ts">
import { getNode, type FormKitNode } from "@formkit/core";
import { Post, PostUpdateRequest } from "~/types/api/post";

interface IPostEditInterface {
  post: Post;
}
const props = defineProps<IPostEditInterface>();
const emit = defineEmits<{
  submit: [];
}>();

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
      <FormKit type="form" @submit="submit" :value="{
        'title': $props.post.title,
        'slug': $props.post.slug,
        'body': $props.post.body,
      }">
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
          disabled
        />
        <FormKit
          type="textarea"
          name="body"
          id="body"
          label="Body"
          placeholder="My great post"
          validation="required"
        />
      </FormKit>
    </div>
  </div>
</template>
