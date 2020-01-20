<template>
  <div class="bz-section-container bz-sec--footer-9-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="sm:tw-flex tw-items-center">
          <div class="tw-w-full max-sm:tw-hidden">
            <div v-for="(page, index) of allPages" :key="index" class="bz-pages-menu">
              <div class="menu-item" @click="pageUrl(page.url)" v-if="showMenuItem(page)">
                <page-link :name="page.name" />
              </div>
            </div>
          </div>

          <div class="tw-w-full">
            <div class="tw-mt-6">
              <bz-social-icons :edit="true" />
            </div>

            <div class="tw-my-6 tw-w-full">
              <bz-title v-model="data.elements.title" />
            </div>

            <div class="tw-flex tw-items-center">
              <span class="copyright-symbol">&copy; </span>
              <bz-text v-model="data.elements.copyrightMessage" />
            </div>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer9',
  components: {
    PageLink,

    BzTitle,
    BzSocialIcons,
    BzContainer,
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
.bz-sec--footer-9-root {
  @import 'style';
  .justify-space-between {
    justify-content: space-between;
  }

  .wrapper-1 {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  * {
    color: var(--bz-theme-text-color) !important;
  }
}
</style>
