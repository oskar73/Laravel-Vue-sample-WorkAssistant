<template>
  <div class="settings_area" :class="{ active: activeSlider === 'settings' }">
    <div class="settings_menu">
      <div class="pb-2 px-3 pt-4">
        <div class="row align-items-center">
          <div class="col-10 d-flex align-self-center">
            <h5 class="mb-0 text-dark"><b>Settings</b></h5>
          </div>
        </div>
        <div class="setting-menu-item" :class="{ active: activeTab === 0 }" @click="activeTab = 0">
          <web-icon fill-color="#555555" />
          <div class="menu-text">{{ isWebsite ? 'Website' : 'Template' }} Setting</div>
        </div>
        <div class="setting-menu-item" :class="{ active: activeTab === 1 }" @click="activeTab = 1">
          <business-icon fill-color="#555555" />
          <div class="menu-text">Business</div>
        </div>
        <div
          v-for="(business, index) of settings.businesses"
          :key="index"
          class="company-name"
          :class="{ active: activeBusinessIndex === index && activeTab === 1 }"
          @click="openBusiness(index)"
        >
          <div class="my-auto">{{ business.companyName }}</div>
          <div class="ml-auto my-auto mr-2 delete-btn" @click="removeBusiness(index)">
            <i class="mdi mdi-delete"></i>
          </div>
        </div>
        <div class="company-name" :class="{ active: activeBusinessIndex === -1 }" @click="openBusiness(-1)">
          <div class="my-auto">New Business</div>
        </div>
        <div class="setting-menu-item" :class="{ active: activeTab === 2 }" @click="activeTab = 2">
          <account-circle-icon fill-color="#555555" />
          <div class="menu-text">Social accounts</div>
        </div>
        <div v-if="false" class="setting-menu-item" :class="{ active: activeTab === 3 }" @click="activeTab = 3">
          <multiline-chart fill-color="#555555" />
          <div class="menu-text">Tracking & Analytics</div>
        </div>
        <div v-if="false" class="setting-menu-item" :class="{ active: activeTab === 4 }" @click="activeTab = 4">
          <policy-icon fill-color="#555555" />
          <div class="menu-text">Legal</div>
        </div>
        <div v-if="false" class="setting-menu-item" :class="{ active: activeTab === 5 }" @click="activeTab = 5">
          <code-icon fill-color="#555555" />
          <div class="menu-text">HTML injection</div>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'settings'" class="settings_content">
      <div v-show="activeTab === 0" class="flex-column h-100" style="display: flex">
        <div class="w-100 p-3">
          <div class="row">
            <div class="col-10">
              <h4 class="tw-font-semibold">Template Setting</h4>
            </div>
            <div class="col-2 text-right">
              <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
                <i class="mdi mdi-close tw-text-lg"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100" style="flex-grow: 1">
          <tabs :options="{ disableScrollBehavior: true }">
            <tab name="Template Name">
              <div class="p-3">
                <bz-input v-model="template.name" size="medium" :label="isWebsite ? 'Website Name' : 'Template Name'" />
              </div>
            </tab>
            <tab name="Template Preview">
              <div class="p-3">
                <image-selector v-model="template.image" :sync-s3="false" />
              </div>
            </tab>
            <tab name="Logo">
              <div class="px-3">
                <image-selector v-model="template.data.logo.url" label="Select Upload Logo" preview-width="300px" />
                <div v-if="isWebsite" class="mt-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <b class="bz-text-active cursor-pointer" @click="toggleChooseLogo('my-logo')">Choose from my Logos</b>
                    <a href="/graphics/category/logo" class="btn btn-info btn-sm text-white"> <i class="fa fa-plus"></i> Create a New Logo</a>
                  </div>
                  <div v-if="chooseLogo === 'my-logo'">
                    <template v-if="userLogos === null">
                      <bz-loading />
                    </template>
                    <template v-else-if="!userLogos.length">
                      <bz-warning>
                        <p style="margin-bottom: 20px">You have no logos</p>
                      </bz-warning>
                    </template>
                    <template v-else>
                      <div style="--max-width: 150px; margin-top: 10px" class="bz-grid-row">
                        <template v-for="(logo, index) of userLogos">
                          <div v-if="logo.preview.content" :key="index" class="logo-item-wrap" @click="handleLogoItemClick(logo.preview.content)">
                            <img :src="logo.preview.content" :alt="logo.name" />
                          </div>
                        </template>
                      </div>
                      <div class="float-right">
                        <span class="bz-text-active cursor-pointer" @click="loadMoreLogos">Load more</span>
                      </div>
                    </template>
                  </div>
                </div>
                <div class="mt-3">
                  <!--                  <b class="bz-text-active cursor-pointer" @click="toggleChooseLogo('stock-logo')">Choose from stock Logos</b>-->
                  <div v-if="chooseLogo === 'stock-logo'">
                    <template v-if="stockLogos === null">
                      <bz-loading />
                    </template>
                    <template v-else-if="stockLogos === []">
                      <bz-warning>
                        <p style="margin-bottom: 20px">Something went to wrong</p>
                      </bz-warning>
                    </template>
                    <template v-else>
                      <div style="--max-width: 150px; margin-top: 10px" class="bz-grid-row">
                        <template v-for="(logo, index) of stockLogos">
                          <div v-if="logo.preview" :key="index" class="logo-item-wrap" @click="handleLogoItemClick(logo.preview)">
                            <img :src="logo.preview" :alt="logo.name" />
                          </div>
                        </template>
                      </div>
                      <div class="float-right">
                        <span class="bz-text-active cursor-pointer" @click="loadMoreLogos">Load more</span>
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </tab>
            <tab name="Favicon">
              <div class="px-3">
                <image-selector v-model="template.data.favicon.url" :aspect-ratio="1" label="Upload Favicon" preview-width="200px" />
                <div v-if="isWebsite" class="mt-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <b class="bz-text-active cursor-pointer" @click="toggleChooseFavicon('my-favicon')">Choose from my Favicons</b>
                    <a href="/favicon/categories" class="btn btn-info btn-sm text-white"> <i class="fa fa-plus"></i> Create a New Favicon</a>
                  </div>
                  <div v-if="chooseFavicon === 'my-favicon'">
                    <template v-if="userFavicons === null">
                      <bz-loading />
                    </template>
                    <template v-else-if="!userFavicons.length">
                      <bz-warning>
                        <p>You have no favicons</p>
                      </bz-warning>
                    </template>
                    <template v-else>
                      <div style="--max-width: 150px; margin-top: 10px" class="bz-grid-row">
                        <template v-for="(favicon, index) of userFavicons">
                          <div v-if="favicon.preview.content" :key="index" class="logo-item-wrap" @click="handleFaviconItemClick(favicon.preview.content)">
                            <img :src="favicon.preview.content" :alt="favicon.name" />
                          </div>
                        </template>
                      </div>
                      <div class="float-right">
                        <span class="bz-text-active cursor-pointer" @click="loadMoreLogos">Load more</span>
                      </div>
                    </template>
                  </div>
                </div>
                <div class="mt-3">
                  <div v-if="chooseFavicon === 'stock-favicon'">
                    <template v-if="stockFavicons === null">
                      <bz-loading />
                    </template>
                    <template v-else-if="stockFavicons === []">
                      <bz-warning>
                        <p>Something went to wrong</p>
                      </bz-warning>
                    </template>
                    <template v-else>
                      <div style="--max-width: 150px; margin-top: 10px" class="bz-grid-row">
                        <template v-for="(favicon, index) of stockFavicons">
                          <div v-if="favicon.preview" :key="index" class="logo-item-wrap" @click="handleFaviconItemClick(favicon.preview)">
                            <img :src="favicon.preview" :alt="favicon.name" />
                          </div>
                        </template>
                      </div>
                      <div class="float-right">
                        <span class="bz-text-active cursor-pointer" @click="loadMoreLogos">Load more</span>
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </tab>
          </tabs>
        </div>
      </div>

      <business-address-list v-show="activeTab === 1" :active-business-index="activeBusinessIndex" />

      <template v-if="activeTab === 2">
        <div>
          <div class="w-100">
            <div class="w-100 p-3 bottom-bordered">
              <div class="row">
                <div class="col-10">
                  <h4>Social Accounts</h4>
                </div>
                <div class="col-2 text-right">
                  <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
                    <i class="mdi mdi-close"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="w-100">
            <div class="p-2">
              <p class="px-4 py-2">
                Here you can add the URLs of your social accounts which will be shown in your site's footer, allowing your visitors to go directly to yoru social accounts.
              </p>
              <div class="col-12 px-4">
                <div class="row">
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.bizinabox.url" label="Bizinabox" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.bizinabox.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.facebook.url" label="Facebook" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.facebook.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.instagram.url" label="Instagram" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.instagram.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.linkedin.url" label="LinkedIn" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.linkedin.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.pinterest.url" label="Pinterest" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.pinterest.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.reddit.url" label="Reddit" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.reddit.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.tiktok.url" label="TikTok" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.tiktok.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.twitter.url" label="Twitter" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.twitter.show" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <bz-input v-model="settings.socialAccounts.youtube.url" label="YouTube" :height="42" />
                      <bz-switch v-model="settings.socialAccounts.youtube.show" />
                    </div>
                  </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                  <button class="btn bz-btn-default" @click="saveTemplateSettings">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <template v-if="activeTab === 3 && false">
        <div class="w-100 p-3 bottom-bordered">
          <div class="row">
            <div class="col-10">
              <h4>Tracking and Analytics</h4>
            </div>
            <div class="col-2 text-right">
              <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
                <i class="mdi mdi-close"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100">
          <div class="p-2">
            <p class="p-4">Add your Google Analytics Tracking ID to here to add Google's tracking code to every page of your website.</p>
            <div class="row">
              <div class="col-12">
                <input v-model="settings.googleAnalyticsTrackingId" label="Google analytics tracking ID" placeholder="US-XXXXXXX(-YY)" />
              </div>
            </div>
            <div class="mt-4">
              <div>Don't have a Google Analytics ID?</div>
              <a href="https://google.com/analytics" target="_blank">Create a Google Analytics account</a>
            </div>
          </div>
        </div>
      </template>

      <div v-if="activeTab === 4 && false">
        <div class="w-100 p-3">
          <div class="row">
            <div class="col-10">
              <h4>Legal</h4>
            </div>
            <div class="col-2 text-right">
              <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
                <i class="mdi mdi-close"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100">
          <tabs :options="{ disableScrollBehavior: true }">
            <tab id="tab-terms-of-service" name="Terms of Service">
              <div class="w-100 mt-3">
                <div class="row">
                  <div class="col-12">
                    <bz-input v-model="settings.termsOfService" :multiple="true" label="Terms of Service" />
                  </div>
                </div>
              </div>
            </tab>
            <tab id="tab-privacy-policy-and-gdpr" name="Privacy Policy and GDPR">
              <div class="w-100 mt-3">
                <div class="row">
                  <div class="col-12">
                    <bz-select v-model="settings.cookieBannerPosition" :options="['top', 'bottom']" :min-height="38" variant="white" label="Cookie banner position" />
                  </div>
                  <div class="col-12">
                    <bz-input v-model="settings.bannerText" label="Banner text" />
                  </div>
                  <div class="col-12">
                    <bz-input v-model="settings.agreeButtonText" label="Agree button text" />
                  </div>
                  <div class="col-12">
                    <bz-input v-model="settings.privacyPolicy" :multiple="true" label="Privacy Policy" />
                  </div>
                </div>
              </div>
            </tab>
          </tabs>
        </div>
      </div>

      <template v-if="activeTab === 5 && false">
        <div class="w-100 p-3">
          <div class="row">
            <div class="col-10">
              <h4>HTML injection</h4>
            </div>
            <div class="col-2 text-right">
              <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
                <i class="mdi mdi-close"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100">
          <tabs>
            <tab id="tab-head-html" name="Head HTML" />
            <tab id="tab-body-and-html" name="Body and HTML" />
          </tabs>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import WebIcon from '../icons/Web.vue'
import BusinessIcon from '../icons/Business.vue'
import AccountCircleIcon from '../icons/AccountCircle.vue'
import MultilineChart from '../icons/MultilineChart.vue'
import PolicyIcon from '../icons/Policy.vue'
import CodeIcon from '../icons/Code.vue'
import BzInput from './BzInput.vue'
import ImageSelector from '../elements/ImageSelector.vue'
import BzLoading from '../elements/BzLoading.vue'
import BzWarning from '../section/BzWarning.vue'
import BusinessAddressList from './BusinessAddressList.vue'
import BzSelect from './BzSelect.vue'
import { cloneDeep } from 'lodash'
import BzSwitch from './BzSwitch.vue'
import builderMixin from '../../mixins/builderMixin'

export default {
  name: 'TemplateSettings',
  components: {
    BzSwitch,
    BzSelect,
    BusinessAddressList,
    BzWarning,
    BzLoading,
    ImageSelector,
    BzInput,
    CodeIcon,
    PolicyIcon,
    MultilineChart,
    AccountCircleIcon,
    BusinessIcon,
    WebIcon
  },
  mixins: [builderMixin],
  data() {
    return {
      chooseFavicon: 'upload', // upload, stock-favicon, my-favicon
      chooseLogo: 'upload', // upload, stock-logo, my-logo,
      activeBusinessIndex: 0
    }
  },
  computed: {
    activeTab: {
      get() {
        return this.$store.state.activeTab
      },
      set(val) {
        this.$store.commit('layoutSetActiveTab', val)
      }
    },
    userLogos() {
      return this.$store.state.userLogos
    },
    userFavicons() {
      return this.$store.state.userFavicons
    },
    stockLogos() {
      console.log(this.$store.state.stockLogos)
      return this.$store.state.stockLogos
    },
    stockFavicons() {
      return this.$store.state.stockFavicons
    },
    settings: {
      get() {
        return this.templateSetting
      },
      set(v) {
        this.templateSetting = v
      }
    }
  },
  methods: {
    openBusiness(index) {
      if (this.activeTab !== 1) {
        this.activeTab = 1
      }
      this.activeBusinessIndex = index
    },
    handleLogoItemClick(logo) {
      if (logo.includes(';base64')) {
        this.template.data.logo.url = logo
      } else {
        this.template.data.logo.url = logo + '?v=' + new Date().getTime()
      }
      this.template = cloneDeep(this.template)
    },
    handleFaviconItemClick(favicon) {
      if (favicon) {
        if (favicon.includes(';base64')) {
          this.template.data.favicon.url = favicon
        } else {
          this.template.data.favicon.url = favicon + '?' + new Date().getTime()
        }
        this.template = cloneDeep(this.template)
      } else {
        window.LOG.error('Favicon does not exist')
      }
    },
    toggleChooseLogo(source) {
      if (this.chooseLogo === 'upload') {
        this.chooseLogo = source
        if (source === 'my-logo') {
          if (this.userLogos == null) {
            this.$store.commit('loadUserLogos')
          }
        } else {
          if (this.stockLogos === null) {
            this.$store.commit('loadStockLogos')
          }
        }
      } else {
        this.chooseLogo = 'upload'
      }
    },
    loadMoreLogos() {
      if (this.chooseLogo === 'stock-logo') {
        let page = Math.ceil(((this.stockLogos || []).length - 1) / 10)
        if (page < 0) page = 0
        console.log('load stock logos page', page)
        this.$store.commit('loadStockLogos', page)
      } else if (this.chooseLogo === 'my-logo') {
        let page = Math.ceil(((this.userLogos || []).length - 1) / 10)
        if (page < 0) page = 0
        console.log('load my logos page', page)
        this.$store.commit('loadUserLogos', page)
      }
    },
    toggleChooseFavicon(source) {
      if (this.chooseFavicon === 'upload') {
        this.chooseFavicon = source
        if (source === 'my-favicon') {
          if (this.userFavicons === null) {
            this.$store.commit('loadUserFavicons')
          }
        } else {
          if (this.stockFavicons === null) {
            this.$store.commit('loadStockFavicons')
          }
        }
      } else {
        this.chooseFavicon = 'upload'
      }
    },
    saveTemplateSettings() {
      this.templateSetting = cloneDeep(this.settings)
    }
  }
}
</script>

<style lang="scss">
$active: #0076df;
.settings_area {
  width: 900px;
  height: calc(100vh - 60px);
  position: fixed;
  left: 70px;
  top: 60px;
  background-color: rgb(239, 240, 241);
  z-index: 51;
  overflow: hidden;
  display: flex;
  box-shadow: 2px 0 2px 1px #00000027;
  transform: translateX(-900px);
  //transition: transform 0.3s linear;

  &.active {
    transform: translateX(0px);

    .bz-close-section-area {
      &::after {
        display: block;
      }
    }
  }

  .bottom-bordered {
    border-bottom: solid 1px #0000001a;
  }

  .settings_menu {
    width: 300px;
    background-color: #eff0f1;
    border: solid 1px #8080801a;

    .setting-menu-item {
      border-radius: 4px;
      padding: 10px;
      display: flex;
      align-items: center;
      cursor: pointer;

      &:first-child {
        margin-top: 30px;
      }

      .menu-text {
        padding-left: 10px;
        color: #555555;
      }

      &.active {
        background-color: #0000001a;

        .menu-text {
          font-weight: bold;
        }
      }
    }

    .company-name {
      padding: 10px 0 10px 40px;
      margin-top: 5px;
      margin-left: 5px;
      margin-bottom: 5px;
      cursor: pointer;
      border-radius: 4px;
      display: flex;

      &.active {
        font-weight: bold;
      }

      &:hover {
        background-color: #0000001a;
      }

      .delete-btn:hover {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
      }
    }
  }

  .settings_content {
    width: 600px;
    background-color: white;
    display: flex;
    flex-direction: column;

    .md-tabs {
      height: 100%;

      .md-tabs-container {
        height: 100%;
      }

      .md-tab {
        overflow-y: auto;
        height: 100%;
      }

      .md-tabs-container {
        height: 100%;
      }
    }

    .md-content.md-tabs-content.md-theme-default {
      flex-grow: 1;
    }
  }

  .md-tabs {
    .md-button-content {
      text-transform: capitalize;
    }

    .md-tabs-navigation.md-elevation-0 {
      background-color: white;
      border-bottom: solid 1px #8080803f;
    }
  }

  .logo-item-wrap {
    width: 100%;
    height: 100%;
    border: solid 1px #00000012;
    cursor: pointer;

    &:hover {
      outline: solid 2px var(--bz-edit-active);
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  }
}
</style>
