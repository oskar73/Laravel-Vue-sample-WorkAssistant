<template>
  <div class="bz-section-container bz-sec--footer-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="sm:tw-grid sm:tw-grid-cols-4 tw-gap-4">
          <div class="tw-col-span-2 tw-mb-4">
            <bz-logo :title="setting.elements.siteTitle" />
            <div class="tw-my-2" v-if="setting.elements.description">
              <bz-text v-model="data.elements.description" />
            </div>
            <bz-social-icons v-if="setting.elements.socialIcons" />
          </div>
          <div v-if="setting.elements.pagesMenu" class="tw-justify-between bz-pages-menu tw-mb-4">
            <bz-title v-model="data.elements.pageMenuTitle" />
            <bz-nav-bar :vertical="true">
              <template v-slot="{ pageName }">
                <page-link :name="pageName" class="mr-3" />
              </template>
            </bz-nav-bar>
          </div>
          <div>
            <template v-if="setting.elements.address">
              <bz-title v-model="data.elements.addressTitle" />

              <bz-address :location="setting.businessInformation.location" />
            </template>

            <bz-phone-number v-if="setting.elements.phoneNumber" />

            <bz-email v-if="setting.elements.email" />
          </div>
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="w-100 tw-flex align-items-center" v-if="setting.elements.copyrightMessage">
          <bz-setting :wrap-content="true" @click="openBusinessSetting">
            <bz-text
              :edit-mode="false"
              style="font-size: 14px; font-weight: 300"
              :model-value="`&copy; ${new Date().getFullYear()} ${templateSetting.businesses[activeCompanyIndex].companyName}`"
            />
          </bz-setting>
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
import BzDivider from '../../components/section/BzDivider.vue'

import BzAddress from '../../components/section/BzAddress.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzSetting from '../../components/section/BzSetting.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer1',
  components: {
    PageLink,
    BzNavBar,
    BzSocialIcons,
    BzSetting,
    BzEmail,
    BzTitle,
    BzPhoneNumber,
    BzAddress,

    BzDivider,
    BzContainer,
    BzBackground,
    BzLogo
  },
  extends: BaseFooter,
  methods: {
    openBusinessSetting() {
      this.openSettingSlider(1, 'tab-address')
    }
  },
  mounted() {
    if (!this.data.elements.pageMenuTitle) this.data.elements.pageMenuTitle = 'Pages'
  }
})
</script>
<style lang="scss" scoped>
.bz-sec--footer-1-root {
  @import 'style';
}
</style>
