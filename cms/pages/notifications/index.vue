<script setup lang="ts">
import { storeToRefs } from "pinia";
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

// TODO: Add Notification interface
const markAsRead = (notification: any) => {
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

const view = (notification: any) => {
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
    <div class="col-span-12 px-4 sm:px-8 flex justify-end">
      <BaseButton @click="markAllAsRead"> Mark All as Read </BaseButton>
    </div>
    <div class="col-span-12">
      <ul
        class="flex flex-col gap-4 max-h-[calc(100svh-10rem)] overflow-auto px-4 sm:px-8"
      >
        <li
          v-for="notification in notifications"
          class="bg-slate-100 rounded-lg p-2 border-l-4 border-l-slate-800 shadow-lg"
          :class="{
            'bg-transparent border-l-slate-300': notification.read_at,
          }"
        >
          <div
            class="flex flex-col gap-4"
            v-if="notification.type === 'App\\Notifications\\ThreadReplied'"
          >
            <div class="text-slate-600">
              {{ formatDateDistance(notification.created_at) }}
            </div>
            <div class="text-xl">
              <Icon name="material-symbols:quickreply" /> ~
              {{ notification.data.info }}
            </div>
            <div class="flex flex-col gap-3">
              <div class="">
                <div class="italic">Thread:</div>
                <div class="prose prose-sm bg-gray-200 p-1 rounded max-w-full">
                  <div v-html="notification.data.data.thread.body"></div>
                </div>
              </div>
              <div class="">
                <div class="italic">Reply:</div>
                <div class="prose prose-sm bg-gray-200 p-1 rounded max-w-full">
                  <div v-html="notification.data.data.reply.body"></div>
                </div>
              </div>
            </div>
            <div class="flex gap-2 justify-end">
              <BaseButton
                v-if="!notification.read_at"
                @click="markAsRead(notification)"
              >
                Mark As Read
              </BaseButton>
              <BaseButton @click="view(notification)"> View </BaseButton>
            </div>
          </div>
        </li>
      </ul>
      <!-- {{ notifications }} -->
    </div>
  </div>
</template>
