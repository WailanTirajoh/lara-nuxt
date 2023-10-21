<script setup lang="ts">
import { storeToRefs } from "pinia";
import type { User } from "~/types/api/user";
const threadStore = useThreadStore();
const { selectedThread } = storeToRefs(threadStore);

const { $echo } = useNuxtApp();
const roomUsers = ref<Array<User>>([]);

watch(selectedThread, (value, oldValue) => {
  if (oldValue) {
    $echo.leave(`thread-presence.${oldValue.id}`);
  }

  if (value) {
    $echo
      .join(`thread-presence.${value.id}`)
      .here((users: Array<User>) => {
        roomUsers.value = users;
      })
      .joining((user: User) => {
        roomUsers.value.push(user);
      })
      .leaving((user: User) => {
        const index = roomUsers.value.findIndex(
          (currentUser) => currentUser.id === user.id
        );
        if (index > -1) {
          roomUsers.value.splice(index, 1);
        }
      });
  }
});
onMounted(() => {});
</script>
<template>
  <div v-if="selectedThread" class="flex flex-col h-full">
    <div class="h-[calc(100%-11rem)] overflow-auto">
      <div class="bg-white grid gap-2 p-2 sticky top-0">
        <div class="">
          <TransitionGroup
            class="flex gap-1 justify-start"
            name="list"
            tag="ul"
          >
            <li :key="user.id" v-for="user in roomUsers">
              <img
                class="w-6 h-6 rounded-full overflow-hidden object-cover"
                :src="user.profile_picture"
                alt=""
              />
            </li>
          </TransitionGroup>
        </div>
        <div class="flex gap-2">
          <img
            :src="selectedThread.user.profile_picture"
            alt=""
            class="w-16 h-16 rounded object-cover object-center"
          />
          <div class="">
            <div class="text-xl">
              {{ selectedThread.user.name }}
            </div>
            <div class="text-slate-600">
              {{ formatDateDistance(selectedThread.created_at) }}
            </div>
          </div>
        </div>
        <div class="prose prose-sm" v-html="selectedThread.body"></div>
      </div>
      <div :key="`reply-list-${selectedThread.id}`" class="bg-gray-50">
        <PageChannelThreadReplyList :thread-id="selectedThread.id.toString()" />
      </div>
    </div>
    <div class="absolute bottom-0 bg-gray-200 p-1 min-h-44 w-full">
      <PageChannelThreadReplyCreate :thread-id="selectedThread.id.toString()" />
    </div>
  </div>
</template>
