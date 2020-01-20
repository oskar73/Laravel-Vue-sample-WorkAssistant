<template>
  <div class="bz-section-container bz-sec--footer-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <bz-logo :title="setting.elements.siteTitle" />
        <div class="tw-my-4">
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.description" />
        </div>
        <div v-if="setting.elements.pagesMenu" class="d-flex align-items-end justify-content-between tw-my-3">
          <div class="sm:tw-flex sm:tw-space-x-4">
            <div v-for="(page, index) of allPages" :key="index" class="menu-item" @click="pageUrl(page.url)">
              <page-link :name="page.name" v-if="showMenuItem(page)" />
            </div>
          </div>
        </div>

        <bz-phone-number v-if="setting.elements.phoneNumber" />

        <div class="tw-my-2">
          <bz-social-icons v-if="setting.elements.socialIcons" :edit="true" />
        </div>
        <div class="tw-w-full tw-flex tw-items-center mt-3">
          <bz-setting :wrap-content="true" @click="openBusinessSetting">
            <bz-text
              :edit-mode="false"
              style="font-size: 14px; font-weight: 300"
              :value="`&copy; ${new Date().getFullYear()} ${templateSetting.businesses[activeCompanyIndex].companyName}`"
            />
          </bz-setting>
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
import BzDivider from '../../components/section/BzDivider.vue'
import BzSetting from '../../components/section/BzSetting.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer2',
  components: {
    PageLink,
    BzSocialIcons,
    BzSetting,
    BzDivider,
    BzContainer,
    BzBackground,
    BzLogo,
    BzPhoneNumber
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
.bz-sec--footer-2-root {
  @import 'style';
}
</style>
