<script>
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue';
export default {
  name: 'Modal',
  components: {Dialog, DialogOverlay, TransitionChild, TransitionRoot},
  props: {
    show: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<template>
  <TransitionRoot as="template" :show="show">
    <Dialog as="div" class="tw-fixed tw-z-[1001] tw-inset-0" @close="$emit('close')">
      <div class="tw-flex tw-items-end tw-justify-center tw-h-screen tw-pt-4 tw-px-4 sm:tw-py-5 tw-text-center tw-sm:block tw-sm:p-0">
        <TransitionChild as="template"
                         enter="tw-ease-out tw-duration-300"
                         enter-from="tw-opacity-0"
                         enter-to="tw-opacity-100"
                         leave="tw-ease-in tw-duration-200"
                         leave-from="tw-opacity-100"
                         leave-to="tw-opacity-0">
          <DialogOverlay class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity"/>
        </TransitionChild>
        <span class="tw-hidden tw-sm:inline-block tw-sm:align-top tw-sm:h-screen" aria-hidden="true">&#8203;</span>
        <TransitionChild as="template"
                         enter="tw-ease-out tw-duration-300"
                         enter-from="tw-opacity-0 tw-translate-y-4 tw-sm:translate-y-0 tw-sm:scale-95"
                         enter-to="tw-opacity-100 tw-translate-y-0 tw-sm:scale-100"
                         leave="tw-ease-in tw-duration-200"
                         leave-from="tw-opacity-100 tw-translate-y-0 tw-sm:scale-100"
                         leave-to="tw-opacity-0 tw-translate-y-4 tw-sm:translate-y-0 tw-sm:scale-95">
          <div class="tw-inline-block tw-align-bottom tw-transform tw-transition-all tw-sm:py-8 tw-sm:align-middle tw-w-full tw-h-full">
            <div class="tw-w-full tw-flex tw-flex-col tw-items-center tw-h-full">
              <slot v-if="show" />
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped lang="scss">

</style>
