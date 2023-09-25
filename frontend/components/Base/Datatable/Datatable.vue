<script setup lang="ts"></script>

<template>
  <div class="overflow-auto w-full border rounded">
    <div class="bg-gray-50">
      <slot name="head"> </slot>
    </div>
    <table
      class="w-full rounded overflow-hidden text-slate-700"
      aria-label="Posts Table"
    >
      <thead class="bg-gray-50 border-b">
        <tr>
          <th scope="col" class="text-slate-900 font-normal p-2 w-12">No</th>
          <th scope="col" class="text-slate-900 font-normal p-2">Title</th>
          <th scope="col" class="text-slate-900 font-normal p-2">Created By</th>
        </tr>
        <tr>
          <th scope="col" class="text-slate-900 font-normal p-1 w-12"></th>
          <th scope="col" class="text-slate-900 font-normal p-1">
            <input
              type="text"
              class="p-2 rounded bg-gray-white w-full focus:bg-white focus:outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title"
            />
          </th>
          <th scope="col" class="text-slate-900 font-normal p-1">
            <input
              type="text"
              class="p-2 rounded bg-gray-white w-full focus:bg-white focus:outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by created by"
            />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="i in 5"
          :key="i"
          class="duration-100 rounded-lg hover:bg-gray-100"
        >
          <td
            class="p-3 border first:border-l-0 last:border-r-0 w-12 text-center"
          >
            {{ i }}
          </td>
          <td class="p-3 border first:border-l-0 last:border-r-0">Test</td>
          <td class="p-3 border first:border-l-0 last:border-r-0">Test</td>
        </tr>
      </tbody>
    </table>
    <div class="p-2">
      <div class="flex items-center justify-between">
        <div class="">Rows per page</div>
        <ul class="flex gap-2">
          <li>
            <BaseButton variant="light">
              <Icon name="ion:chevron-back" />
            </BaseButton>
          </li>
          <li>
            <BaseButton variant="light">
              <Icon name="ion:chevron-forward" />
            </BaseButton>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
