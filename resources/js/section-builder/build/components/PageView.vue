<template>
  <page-layout>
    <div class="tw-flex-1">
      <div v-if="loading" class="tw-flex tw-justify-center tw-items-center tw-h-36">
        <spinner class="tw-w-8 tw-h-8" />
      </div>
      <template v-for="(section, index) of sections">
        <component :is="section.name" v-if="section.data.setting.visible" :key="index" :properties="section" :position="index"></component>
      </template>
    </div>
  </page-layout>
</template>

<script>
import PageLayout from '@/section-builder/build/components/PageLayout.vue'
import { getWebsitePage } from '@/section-builder/apis'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import Spinner from '@/public/Spinner.vue'

export default {
  name: 'PageView',
  components: {
    Spinner,
    BzContainer,
    PageLayout
  },
  props: {
    pageId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      loading: true,
      page: null,
      sections: []
    }
  },
  created() {
    this.loading = true
    getWebsitePage(this.pageId).then(res => {
      this.page = res.data.page
      this.sections = this.page.sections
      this.loading = false
    })
  }
}
</script>
