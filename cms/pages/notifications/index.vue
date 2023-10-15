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
</script>

<template>
  <div class="p-4 sm:p-8 grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Notifications</h1>
    </div>
    <div class="col-span-12">
      <hr />
    </div>
    <div class="col-span-12">
      <ul class="flex flex-col gap-2">
        <li
          class="bg-slate-100 rounded p-2"
          v-for="notification in notifications"
        >
          <div class="flex flex-col gap-4" v-if="notification.type === 'App\\Notifications\\ThreadReplied'">
            <div class="text-xl">
              <Icon name="material-symbols:quickreply" /> ~
              {{ notification.data.info }}
            </div>
            <div class="flex flex-col gap-3">
              <div class="">
                <div class="">Thread:</div>
                <div class="prose prose-sm bg-gray-200 p-1 rounded max-w-full">
                  <div v-html="notification.data.data.thread.body"></div>
                </div>
              </div>
              <div class="">
                <div class="">Reply:</div>
                <div class="prose prose-sm bg-gray-200 p-1 rounded max-w-full">
                  <div v-html="notification.data.data.reply.body"></div>
                </div>
              </div>
            </div>
            <div class="flex justify-end">
              <BaseButton
                @click="
                  navigateTo(
                    `/channels/${notification.data.data.thread.channel_id}?thread=${notification.data.data.thread.id}`
                  )
                "
              >
                View
              </BaseButton>
            </div>
          </div>
        </li>
      </ul>
      <!-- {{ notifications }} -->
    </div>
  </div>
</template>
