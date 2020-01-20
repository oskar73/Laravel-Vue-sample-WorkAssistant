<template>
  <div class="tw-flex sm:tw-flex-row tw-flex-col tw-gap-2">
    <div class="tw-relative tw-flex tw-bg-gray-200">
      <img class="tw-h-full tw-w-full tw-object-contain" src="https://picsum.photos/200" />
    </div>
    <div class="tw-flex tw-items-center">
      <div>
        <p class="tw-mt-2">Listing Title</p>
        <p class="tw-font-medium tw-text-violet-900">Listing Description</p>
        <p class="tw-text-blue-500">Posted On {{ new Date().toLocaleString() }}</p>
      </div>
    </div>
  </div>
</template>