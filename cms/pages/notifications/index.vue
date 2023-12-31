<script setup lang="ts">
import { storeToRefs } from "pinia";
import type { Notification } from "~/types/api/notification";
useSeoMeta({
  title: "Notification",
});
definePageMeta({
  middleware: ["auth"],
});

const notificationStore = useNotificationStore();
const { notifications } = storeToRefs(notificationStore);
onMounted(() => {
  notificationStore.fetchNotifications();
});

const markAsRead = (notification: Notification) => {
  if (!notification.read_at) {
    notificationStore.markAsRead(notification.id).then(() => {
      notificationStore.fetchUnreadNotifications();
    });
    notificationStore.fetchNotifications();
  }
};

const markAllAsRead = async () => {
  await notificationStore.markAllAsRead();
  notificationStore.fetchUnreadNotifications();
  notificationStore.fetchNotifications();
};

const view = (notification: Notification) => {
  markAsRead(notification);
  navigateTo(
    `/channels/${notification.data.data.thread.channel_id}?thread=${notification.data.data.thread.id}`
  );
};
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12 px-4 sm:px-8 pt-4 sm:pt-8">
      <h1 class="text-3xl font-medium">Notifications</h1>
    </div>
    <div class="col-span-12 px-4 sm:px-8">
      <hr />
    </div>
    <div
      v-if="notifications.some((notification) => !notification.read_at)"
      class="col-span-12 px-4 sm:px-8 flex justify-end"
    >
      <BaseButton @click="markAllAsRead"> Mark All as Read </BaseButton>
    </div>
    <div class="col-span-12">
      <ul
        class="flex flex-col gap-4 max-h-[calc(100svh-10rem)] overflow-auto px-4 sm:px-8"
      >
        <template v-if="notifications.length === 0">
          <li class="text-center">
            No notifications
          </li>
        </template>
        <template v-else>
          <li
            v-for="notification in notifications"
            class="bg-slate-100 rounded-lg p-2 border-l-4 border-l-slate-800 border"
            :class="{
              'bg-transparent border-l-slate-300': notification.read_at,
            }"
          >
            <div
              class="flex flex-col gap-4"
              v-if="notification.type === 'App\\Notifications\\ThreadReplied'"
            >
              <div class="text-sm">
                <Icon name="material-symbols:quickreply" /> ~
                {{ notification.data.info }}
              </div>
              <div class="flex flex-col gap-3">
                <div class="">
                  <div class="italic">Reply:</div>
                  <div
                    class="prose prose-sm bg-gray-200 p-1 rounded max-w-full"
                  >
                    <div v-html="notification.data.data.reply.body"></div>
                  </div>
                </div>
              </div>
              <div class="flex gap-2 justify-between">
                <div class="text-slate-600 text-xs">
                  {{ formatDateDistance(notification.created_at) }}
                </div>
                <div class="">
                  <BaseButton
                    v-if="!notification.read_at"
                    @click="markAsRead(notification)"
                  >
                    Mark As Read
                  </BaseButton>
                  <BaseButton @click="view(notification)"> View </BaseButton>
                </div>
              </div>
            </div>
          </li>
        </template>
      </ul>
    </div>
  </div>
</template>
