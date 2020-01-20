<template>
  <div class="bz-section-container bz-sec--footer-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <bz-logo :title="setting.elements.siteTitle" />
        <div class="tw-my-2" v-if="setting.elements.description">
          <bz-text v-model="data.elements.description" />
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="sm:tw-grid tw-grid-cols-2 tw-space-y-4">
          <div v-if="setting.elements.address || setting.elements.phoneNumber">
            <template v-if="setting.elements.address">
              <bz-title v-model="data.elements.addressTitle" />

              <bz-address :location="setting.businessInformation.location" />
            </template>

            <bz-phone-number v-if="setting.elements.phoneNumber" />

            <bz-email v-if="setting.elements.email" />
          </div>
          <div v-if="setting.elements.pagesMenu">
            <div v-for="(page, index) of allPages" :key="index" class="bz-pages-menu">
              <div class="menu-item" @click="pageUrl(page.url)" v-if="showMenuItem(page)">
                <page-link :name="page.name" />
              </div>
            </div>
          </div>
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="tw-w-full">
          <div class="w-100 tw-flex align-items-center tw-mt-4" v-if="setting.elements.copyrightMessage">
            <bz-setting :wrap-content="true" @click="openBusinessSetting">
              <bz-text
                :edit-mode="false"
                style="font-size: 14px; font-weight: 300"
                :model-value="`&copy; ${new Date().getFullYear()} ${templateSetting.businesses[activeCompanyIndex].companyName}`"
              />
            </bz-setting>
          </div>
          <bz-social-icons v-if="setting.elements.socialIcons" :edit="true" />
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
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer5',
  components: {
    PageLink,
    BzLogo,
    BzSocialIcons,
    BzSetting,
    BzEmail,
    BzPhoneNumber,
    BzAddress,
    BzDivider,
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
.bz-sec--footer-5-root {
  @import 'style';
  .justify-space-between {
    justify-content: space-between;
  }
}
</style>
