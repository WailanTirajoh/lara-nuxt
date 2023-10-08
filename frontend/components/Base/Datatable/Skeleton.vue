<template>
  <div class="">
    <div class="overflow-auto w-full border rounded relative">
      <div class="bg-gray-50">
        <slot name="head"> </slot>
      </div>
      <div class="overflow-auto">
        <table
          class="w-full overflow-hidden text-slate-700"
          aria-label="Posts Table"
        >
          <thead class="bg-gray-50 border-b">
            <tr>
              <th
                v-for="column in 5"
                :key="column"
                scope="col"
                class="text-slate-900 font-normal p-2"
              >
                <div class="skeleton !w-16 h-4 rounded"></div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(d, index) in 5"
              class="duration-100 rounded-lg hover:bg-gray-100"
            >
              <td
                v-for="column in 5"
                class="p-3 border first:border-l-0 last:border-r-0 hover:bg-gray-200 duration-100"
              >
                <div class="skeleton !w-24 h-8 rounded"></div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="p-2">
        <div class="flex items-center justify-between">
          <div class="">
            <div class="skeleton !w-16 !h-8 rounded"></div>
          </div>
          <div class="flex items-center gap-2">
            <ul class="flex gap-2">
              <li>
                <div class="skeleton !w-8 !h-8 rounded"></div>
              </li>
              <li>
                <div class="skeleton !w-8 !h-8 rounded"></div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
