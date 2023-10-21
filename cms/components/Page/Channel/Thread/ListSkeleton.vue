<template>
  <ul class="flex flex-col-reverse gap-4 overflow-x-hidden h-full overflow-y-auto p-4">
    <li :key="`skeleton-${i}`" v-for="i in 2">
      <div class="relative flex gap-2 group overflow-x-hidden">
        <span class="skeleton !w-8 !h-8 rounded-lg"></span>
        <div
          class="prose prose-sm bg-white shadow max-w-full w-full p-2 rounded"
        >
          <div class="text-lg">
            <span class="skeleton !w-32 !h-4"></span>
            <span class="ml-4 text-base text-slate-600">
              <span class="skeleton !w-20 !h-4"></span>
            </span>
          </div>
          <div class="flex flex-col gap-2 mt-2">
            <div class="w-full flex flex-wrap gap-2">
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-12 !h-4"></span>
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-4 !h-4"></span>
            </div>
            <div class="w-full flex flex-wrap gap-2">
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-12 !h-4"></span>
            </div>
            <div class="w-full flex flex-wrap gap-2">
              <span class="skeleton !w-12 !h-4"></span>
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-12 !h-4"></span>
            </div>
            <div class="w-full flex flex-wrap gap-2">
              <span class="skeleton !w-12 !h-4"></span>
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-12 !h-4"></span>
            </div>
          </div>
        </div>
      </div>
    </li>
    <li :key="`skeleton-${i}`" v-for="i in 2">
      <div class="relative flex gap-2 group overflow-hidden">
        <span class="skeleton !w-8 !h-8 rounded-lg"></span>
        <div
          class="prose prose-sm bg-white shadow max-w-full w-full p-2 rounded"
        >
          <div class="text-lg">
            <span class="skeleton !w-32 !h-4"></span>
            <span class="ml-4 text-base text-slate-600">
              <span class="skeleton !w-20 !h-4"></span>
            </span>
          </div>
          <div class="flex flex-col gap-2 mt-2">
            <div class="w-full flex flex-wrap gap-2">
              <span class="skeleton !w-12 !h-4"></span>
              <span class="skeleton !w-64 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-32 !h-4"></span>
              <span class="skeleton !w-12 !h-4"></span>
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>
</template>
