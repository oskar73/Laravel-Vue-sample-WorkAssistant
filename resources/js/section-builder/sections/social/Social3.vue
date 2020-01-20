<template>
  <div class="bz-section-container bz-sec--social-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-setting class="mt-5" @click="openSocialLinksForm">
          <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 md:tw-grid-cols-4 lg:tw-grid-cols-6 tw-gap-6">
            <div v-if="templateSetting.socialAccounts.bizinabox.show">
              <bz-editor-link :href="templateSetting.socialAccounts.bizinabox.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-bizinabox-icon />
                </div>
                <bz-text modelValue="Bizinabox" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.pinterest.show">
              <bz-editor-link :href="templateSetting.socialAccounts.pinterest.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-pinterest-icon />
                </div>
                <bz-text modelValue="Pinterest" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.youtube.show">
              <bz-editor-link :href="templateSetting.socialAccounts.youtube.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-youtube-icon />
                </div>
                <bz-text modelValue="Youtube" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.linkedin.show">
              <bz-editor-link :href="templateSetting.socialAccounts.linkedin.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-linkedin-icon />
                </div>
                <bz-text modelValue="Linkedin" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.instagram.show">
              <bz-editor-link :href="templateSetting.socialAccounts.instagram.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-instagram-icon />
                </div>
                <bz-text modelValue="Instagram" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.twitter.show">
              <bz-editor-link :href="templateSetting.socialAccounts.twitter.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-twitter-icon />
                </div>
                <bz-text modelValue="Twitter" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.facebook.show">
              <bz-editor-link :href="templateSetting.socialAccounts.facebook.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-facebook-icon />
                </div>
                <bz-text modelValue="Facebook" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.reddit.show">
              <bz-editor-link :href="templateSetting.socialAccounts.reddit.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-reddit-icon />
                </div>
                <bz-text modelValue="Reddit" :editMode="false" />
              </bz-editor-link>
            </div>

            <div v-if="templateSetting.socialAccounts.tiktok.show">
              <bz-editor-link :href="templateSetting.socialAccounts.tiktok.url" class="w-100 social-link" target="_blank">
                <div class="social-item" :style="itemStyle">
                  <bz-tiktok-icon />
                </div>
                <bz-text modelValue="Tiktok" :editMode="false" />
              </bz-editor-link>
            </div>
          </div>
        </bz-setting>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import eventBus from '@/public/eventBus'
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzSetting from '../../components/section/BzSetting.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import BzBizinaboxIcon from '../../components/social-icons/BzBizinaboxIcon.vue'
import BzPinterestIcon from '../../components/social-icons/BzPinterestIcon.vue'
import BzYoutubeIcon from '../../components/social-icons/BzYoutubeIcon.vue'
import BzLinkedinIcon from '../../components/social-icons/BzLinkedinIcon.vue'
import BzInstagramIcon from '../../components/social-icons/BzInstagramIcon.vue'
import BzTwitterIcon from '../../components/social-icons/BzTwitterIcon.vue'
import BzFacebookIcon from '../../components/social-icons/BzFacebookIcon.vue'
import BzTiktokIcon from '../../components/social-icons/BzTiktokIcon.vue'
import BzRedditIcon from '../../components/social-icons/BzRedditIcon.vue'

export default {
  name: 'Social1',
  components: {
    BzRedditIcon,
    BzTiktokIcon,
    BzFacebookIcon,
    BzTwitterIcon,
    BzInstagramIcon,
    BzLinkedinIcon,
    BzYoutubeIcon,
    BzPinterestIcon,
    BzBizinaboxIcon,
    BzEditorLink,
    BzSetting,

    BzTitle,
    BzAlignment,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin],
  computed: {
    itemStyle() {
      if (this.setting.layouts.shape === 'circle') {
        return { borderRadius: '1000px', color: this.socialIconColor }
      }
      return { color: this.socialIconColor }
    }
  },
  methods: {
    openSocialLinksForm() {
      eventBus.$emit('openSettingSlider', [2])
    }
  }
}
</script>

<style lang="scss">
.bz-sec--social-3-root {
  .social-link {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .social-item {
    border-style: solid;
    border-width: 2px;
    width: 80px;
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 4px;
    margin-bottom: 5px;
  }
}
</style>
