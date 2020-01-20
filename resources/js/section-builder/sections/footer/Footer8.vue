<template>
  <div class="bz-section-container bz-sec--footer-8-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="bz-row">
          <div class="bz-col-md-6">
            <bz-logo :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoSizeChange" />
          </div>
          <div class="bz-col-md-6 d-flex justify-content-end">
            <bz-social-icons />
          </div>
        </div>

        <bz-divider />

        <div class="bz-row mt-3">
          <div class="bz-col-lg-4 bz-col-md-6">
            <div class="d-flex align-items-center">
              <bz-text v-model="data.elements.copyrightMessage" />
            </div>
          </div>
          <div class="bz-col-lg-4 bz-col-md-6">
            <bz-address />
          </div>
          <div class="bz-col-lg-4 bz-col-md-6">
            <bz-text v-model="data.elements.description" />
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
import BzTitle from '../../components/section/BzTitle.vue'
import BzLogo from '../../components/section/BzLogo.vue'

import BaseFooter from './BaseFooter.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default defineComponent({
  name: 'Footer8',
  components: {
    BzDivider,
    BzSocialIcons,
    BzAddress,

    BzLogo,
    BzTitle,
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
.bz-sec--footer-8-root {
  @import 'style';
  .justify-space-between {
    justify-content: space-between;
  }
}
</style>
