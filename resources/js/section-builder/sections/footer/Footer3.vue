<template>
  <div class="bz-section-container bz-lg bz-md bz-sec--footer-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="sm:tw-grid lg:tw-grid-cols-3 sm:tw-grid-cols-2">
          <div class="tw-col-span-2 lg:tw-col-span-1">
            <bz-logo :title="setting.elements.siteTitle" />
              <div class="tw-my-4" v-if="setting.elements.description">
              <bz-text v-model="data.elements.description" />
            </div>
          </div>
          <div>
            <template v-if="setting.elements.pagesMenu">
               <bz-title v-model="data.elements.pageMenuTitle" />
              <div v-for="(page, index) of allPages" :key="index" class="bz-pages-menu">
                <a :href="pageUrl(page.url)" class="menu-item">
                  <page-link :name="page.name" v-if="showMenuItem(page)" />
                </a>
              </div>
            </template>
          </div>
          <div>
            <template v-if="setting.elements.address">
              <bz-title v-model="data.elements.addressTitle" />

              <bz-address :location="setting.businessInformation.location" />
            </template>

            <bz-phone-number v-if="setting.elements.phoneNumber" />

            <bz-email v-if="setting.elements.email" />

            <bz-social-icons v-if="setting.elements.socialIcons" :edit="true" />
          </div>
        </div>
        <bz-divider v-if="setting.elements.dividerLine" :line="true" :line-color="lineColor" />
        <div class="w-100 tw-flex tw-items-center" v-if="setting.elements.copyrightMessage">
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
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzEmail from '../../components/section/BzEmail.vue'

export default defineComponent({
  name: 'Footer3',
  components: {
    PageLink,
    BzLogo,
    BzSocialIcons,
    BzSetting,
    BzDivider,
    BzContainer,
    BzBackground,
    BzAddress,
    BzPhoneNumber,
    BzEmail
  },
  extends: BaseFooter,
  mounted() {
    if (!this.data.elements.pageMenuTitle) this.data.elements.pageMenuTitle = 'Pages'
  },
  methods: {
    openBusinessSetting() {
      this.openSettingSlider(1, 'tab-address')
    }
  }
})
</script>
<style lang="scss" scoped>
.bz-sec--footer-3-root {
  @import 'style';
}
</style>
