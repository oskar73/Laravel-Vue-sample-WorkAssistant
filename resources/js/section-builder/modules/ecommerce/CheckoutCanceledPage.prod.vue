<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/build/components/PageLayout.vue'

export default defineComponent({
  name: 'CheckoutCanceledPage',
  components: { PageLayout }
})
</script>

<template>
  <page-layout>
    <main class="tw-container tw-p-12 tw-mx-auto">
      <nav class="">
        <ul class="tw-flex tw-items-center">
          <li class="tw-cursor-pointer">
            <a href="{{route('home')}}">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                <path
                  d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"
                />
              </svg>
            </a>
          </li>
          <li>
            <span class="tw-mx-2 tw-text-gray-500">&gt;</span>
          </li>

          <li class="tw-text-gray-500">Checkout</li>
        </ul>
      </nav>
      <div class="tw-container tw-py-12 tw-mx-auto">
        <div class="tw-bg-white tw-p-8 tw-rounded-lg tw-shadow-md tw-max-w-md tw-w-full tw-text-center tw-mx-auto">
          <h1 class="tw-text-2xl tw-font-semibold tw-mb-4">Checkout Canceled</h1>

          <p class="tw-mb-4">Your payment was not successful.</p>

          <p class="tw-text-gray-600">If you have any questions or need assistance, please contact our customer support.</p>

          <div class="tw-mt-8">
            <a href="{{route('home')}}" class="tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded-md hover:tw-bg-blue-600"> Back to Home </a>
          </div>
        </div>
      </div>
    </main>
  </page-layout>
</template>
