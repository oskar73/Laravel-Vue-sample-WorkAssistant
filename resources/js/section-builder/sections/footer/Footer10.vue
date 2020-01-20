<template>
  <div class="bz-section-container bz-sec--footer-10-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <div class="sm:tw-flex tw-px-6">
        <div class="tw-w-full">
          <div class="tw-flex tw-items-center">
            <span class="copyright-symbol"> &copy; {{ new Date().getFullYear() }} {{ template.name }}. All Rights Reserved </span>
          </div>
        </div>
        <div class="tw-w-full tw-p-4" :style="{ backgroundColor: boxColor }">
          <div v-for="(page, index) of allPages" :key="index" class="bz-pages-menu">
            <div class="menu-item" @click="pageUrl(page.url)" v-if="showMenuItem(page)">
              <page-link :name="page.name" />
            </div>
          </div>
        </div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer10',
  components: {
    PageLink,
    BzBackground
  },
  extends: BaseFooter,
  methods: {
    openBusinessSetting() {
      this.openSettingSlider(1, 'tab-address')
    }
  }
})
</script>
<style lang="scss" scoped>
.bz-sec--footer-10-root {
  @import 'style';
}
</style>
