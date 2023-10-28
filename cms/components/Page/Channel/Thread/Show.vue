<script setup lang="ts">
import { storeToRefs } from "pinia";
import type { Profile } from "~/types/api/auth";
import type { User } from "~/types/api/user";

const threadStore = useThreadStore();
const { selectedThread } = storeToRefs(threadStore);

const { $echo } = useNuxtApp();
const roomUsers = ref<Array<User>>([]);

type ActiveUser = Record<number, Profile>;
const activeUsers = ref<ActiveUser>({});

const resetActiveUser = useDebounceFn((id: number) => {
  delete activeUsers.value[id];
}, 3000);

const threadShowContainer = ref<HTMLElement>();

const scrollToBottomChat = async () => {
  if (threadShowContainer.value) {
    await nextTick();
    threadShowContainer.value.scrollTop =
      threadShowContainer.value.scrollHeight;
  }
};

const threadReplyStore = useThreadReplyStore();
const { replies } = storeToRefs(threadReplyStore);
watch(selectedThread, (value, oldValue) => {
  if (oldValue) {
    $echo.private(`thread.${oldValue.id}`).stopListening(".replied");

    $echo.leave(`thread-presence.${oldValue.id}`);
  }

  if (value) {
    $echo.private(`thread.${value.id}`).listen(".replied", async (e: any) => {
      replies.value = [...replies.value, e.reply];
      scrollToBottomChat();
    });

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
      })
      .listenForWhisper("typing", (response: Profile) => {
        activeUsers.value = {
          [response.id]: response,
          ...toRaw(activeUsers.value),
        };
        resetActiveUser(response.id);
      });
  }
});
</script>
<template>
  <div v-if="selectedThread" class="flex flex-col h-full">
    <div
      ref="threadShowContainer"
      class="h-[calc(100%-13.5rem)] overflow-auto scroll-smooth"
    >
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
        <PageChannelThreadReplyList
          :thread-id="selectedThread.id.toString()"
          @reply-fetched="() => scrollToBottomChat()"
        />
      </div>
    </div>
    <div class="absolute bottom-0 bg-gray-200 p-1 min-h-44 w-full">
      <div v-for="activeUser in activeUsers">
        {{ activeUser.name }} is typing ...
      </div>
      <PageChannelThreadReplyCreate :thread-id="selectedThread.id.toString()" />
    </div>
  </div>
</template>
