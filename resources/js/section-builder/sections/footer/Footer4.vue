<template>
  <div class="bz-section-container bz-sec--footer-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="sm:tw-flex-row tw-flex sm:tw-items-end justify-content-between tw-flex-col tw-flex-col-reverse">
          <div v-if="setting.elements.pagesMenu" class="bz-pages-menu tw-w-full">
            <bz-title v-model="data.elements.pageMenuTitle" />
            <div v-for="(page, index) of allPages" :key="index" class="menu-item" @click="pageUrl(page.url)">
              <page-link :name="page.name" v-if="showMenuItem(page)" />
            </div>
          </div>
          <bz-logo :title="setting.elements.siteTitle" />
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="sm:tw-grid tw-grid-cols-2">
          <div v-if="setting.elements.address || setting.elements.phoneNumber">
            <template v-if="setting.elements.address">
              <bz-title v-model="data.elements.addressTitle" />

              <bz-address :location="setting.businessInformation.location" />
            </template>

            <bz-phone-number v-if="setting.elements.phoneNumber" />

            <bz-email v-if="setting.elements.email" />
          </div>
          <div>
            <template v-if="setting.elements.description">
              <bz-title v-if="setting.elements.description" v-model="data.elements.descriptionTitle" />

              <bz-text v-model="data.elements.description" />
            </template>

            <bz-social-icons v-if="setting.elements.socialIcons" />
          </div>
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="w-100 d-flex align-items-center">
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
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzSetting from '../../components/section/BzSetting.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer4',
  components: {
    PageLink,
    BzLogo,
    BzSetting,
    BzEmail,
    BzPhoneNumber,
    BzAddress,
    BzSocialIcons,
    BzDivider,
    BzTitle,
    BzContainer,
    BzBackground
  },
  extends: BaseFooter,
  methods: {
    openBusinessSetting() {
      this.openSettingSlider(1, 'tab-address')
    },
    mounted() {
      if (!this.data.elements.pageMenuTitle) this.data.elements.pageMenuTitle = 'Pages'
    }
  }
})
</script>
<style lang="scss" scoped>
.bz-sec--footer-4-root {
  @import 'style';
}
</style>
