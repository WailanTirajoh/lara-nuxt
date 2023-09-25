<template>
  <div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
      <h1 class="text-3xl font-medium">Posts</h1>
    </div>
    <div class="col-span-12">
      <BaseDatatable>
        <template #head>
          <div class="flex gap-2 p-2">
            <input
              type="text"
              class="p-2 rounded bg-gray-200 w-full focus:bg-white focus:outline-none focus:ring focus:ring-violet-300 duration-300"
              placeholder="Search by title, or created by"
            />
            <BaseButton class="w-24">Add Post</BaseButton>
            <BaseButton class="p-0 text-2xl" variant="none">
              <Icon name="ei:refresh" />
            </BaseButton>
          </div>
        </template>
      </BaseDatatable>
    </div>
  </div>
</template>
