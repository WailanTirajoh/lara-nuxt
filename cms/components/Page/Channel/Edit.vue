<script setup lang="ts">
import { type FormKitNode } from "@formkit/core";
import type { Channel, ChannelUpdateRequest } from "~/types/api/channel";
import BaseSelectMulti from "~/components/Base/Select/Multi.vue";
import { createInput } from "@formkit/vue";

interface PageChannelUserManagementProps {
  channel: Channel;
}
const props = defineProps<PageChannelUserManagementProps>();
const emit = defineEmits<{
  submit: [];
}>();

const baseSelectMulti = createInput(BaseSelectMulti, {
  props: ["toptions"],
});

const channelStore = useChannelStore();
async function submit(body: ChannelUpdateRequest, node: FormKitNode) {
  const { data, error } = await channelStore.update(
    props.channel.id.toString(),
    body
  );

  if (error.value?.data) {
    node.setErrors(error.value.data.errors);
  }

  if (data.value) {
    emit("submit");
  }
}

const userStore = useUserStore();
const users = ref<
  Array<{
    label: string;
    value: any;
  }>
>([]);
onMounted(async () => {
  const { data } = await userStore.$get({
    limit: 1000,
    page: 1,
    query: "",
  });
  users.value =
    data.users.map((user) => {
      return {
        label: user.name,
        value: user.id,
      };
    }) ?? [];
});
</script>

<template>
  <div class="grid grid-cols-12">
    <div class="col-span-12">
      <FormKit
        type="form"
        @submit="submit"
        :value="{
          ...$props.channel,
          users: $props.channel.users.map((user) => user.id),
        }"
      >
        <FormKit
          type="text"
          name="name"
          id="name"
          label="Name"
          placeholder="My great post"
          validation="required"
        />
        <FormKit
          :type="baseSelectMulti"
          label="Users"
          name="users"
          help="Select your users."
          :toptions="users"
        />
      </FormKit>
    </div>
  </div>
</template>
