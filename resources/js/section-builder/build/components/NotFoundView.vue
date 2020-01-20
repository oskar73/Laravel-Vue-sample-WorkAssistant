<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/build/components/PageLayout.vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import appMixin from '@/section-builder/mixins/appMixin'
import themeMixin from '@/section-builder/mixins/themeMixin'

export default defineComponent({
  name: 'NotFoundView',
  components: { BzContainer, PageLayout },
  mixins: [appMixin, themeMixin]
})
</script>

<template>
  <page-layout>
    <div class="tw-flex-1">
      <bz-container class="tw-h-full">
        <div class="tw-flex tw-flex-col tw-h-full tw-items-center tw-justify-center">
          <h2 class="tw-font-bold tw-text-gray-300" style="font-size: 128px">4 0 4</h2>
          <p>Page Not Found</p>
        </div>
      </bz-container>
    </div>
  </page-layout>
</template>

<style scoped lang="scss">

</style>
