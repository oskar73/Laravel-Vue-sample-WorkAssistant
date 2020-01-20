<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'

export default defineComponent({
  name: 'ModulePage',
  components: { PageLayout }
})
</script>

<template>
  <page-layout>
    <main class="tw-container tw-p-12 tw-mx-auto">
      <div class="tw-container tw-py-12 tw-mx-auto">
        <div class="tw-bg-white tw-p-8 tw-rounded-lg tw-shadow-md tw-max-w-md tw-w-full tw-text-center tw-mx-auto">
          <h1 class="tw-text-2xl tw-font-semibold tw-mb-4">You cannet edit page in builder.</h1>

          <p class="tw-mb-4">Please visit your site.</p>
        </div>
      </div>
    </main>
  </page-layout>
</template>
