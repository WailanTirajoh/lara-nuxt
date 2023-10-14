<script setup lang="ts">
import { storeToRefs } from "pinia";
const threadStore = useThreadStore();
const { selectedThread } = storeToRefs(threadStore);
</script>
<template>
  <div v-if="selectedThread" class="flex flex-col h-full">
    <div class="h-[calc(100%-11rem)] overflow-auto">
      <div class="bg-white grid gap-2 p-2 sticky top-0">
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
