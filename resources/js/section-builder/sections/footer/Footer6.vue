<template>
  <div class="bz-section-container bz-sec--footer-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="{ footer: true, ...background }" size="s">
      <bz-divider :line="true" :line-color="lineColor" />
      <bz-container class="position-relative">
        <div class="lg:tw-absolute tw-top-0 tw-justify-center max-lg:tw-flex tw-w-full tw-mb-4">
          <bz-logo :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoSizeChange" />
        </div>
        <div class="footer-content-row">
          <bz-social-icons v-if="setting.elements.socialIcons" />

          <div class="d-flex align-items-end justify-content-between">
            <div class="bz-pages-menu">
              <div v-for="(page, index) of allPages" :key="index">
                <bz-page-link :href="pageUrl(page.url)" page="page">
                  <page-link class="menu-item" :name="page.name" v-if="showMenuItem(page)" />
                </bz-page-link>
              </div>
            </div>
          </div>
          <div v-if="setting.elements.description" class="mt-3">
            <bz-text v-model="data.elements.description" />
          </div>
        </div>
      </bz-container>
      <div class="copyright-section">
        <div class="mb-1">
          <bz-text v-model="data.elements.subtitle" />
        </div>
        <div class="tw-w-full tw-flex tw-justify-center" v-if="setting.elements.copyrightMessage">
          <bz-setting :wrap-content="true" @click="openBusinessSetting">
            <bz-text
              :edit-mode="false"
              style="font-size: 14px; font-weight: 300"
              :model-value="`&copy; ${new Date().getFullYear()} ${templateSetting.businesses[activeCompanyIndex].companyName}`"
            />
          </bz-setting>
        </div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzDivider from '../../components/section/BzDivider.vue'

import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzPageLink from '../../components/page/BzPageLink.vue'
import BaseFooter from './BaseFooter.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer6',
  components: {
    PageLink,
    BzPageLink,
    BzSocialIcons,

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
  }
})
</script>
<style lang="scss" scoped>
.bz-sec--footer-6-root {
  @import 'style';

  .bz-pages-menu {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 500px;

    .menu-item {
      text-align: center;
      margin-right: 0;
      padding: 5px 10px;
    }
  }

  .footer-content-row {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .copyright-section {
    padding: 20px 10%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .copyright-symbol {
    font-size: 20px;
    font-weight: bold;
    color: var(--bz-theme-text-cloor);
  }
}
</style>
