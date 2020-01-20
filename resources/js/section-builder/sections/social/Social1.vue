<template>
  <div class="bz-section-container bz-sec--social-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-setting class="tw-mt-10" @click="openSocialLinksForm">
          <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 md:tw-grid-cols-4 lg:tw-grid-cols-6 tw-gap-6">
            <div
              v-if="templateSetting.socialAccounts.bizinabox.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-bizinabox-icon :url="templateSetting.socialAccounts.bizinabox.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.youtube.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-youtube-icon :url="templateSetting.socialAccounts.youtube.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.linkedin.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-linkedin-icon :url="templateSetting.socialAccounts.linkedin.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.instagram.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-instagram-icon :url="templateSetting.socialAccounts.instagram.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.twitter.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-twitter-icon :url="templateSetting.socialAccounts.twitter.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.facebook.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-facebook-icon :url="templateSetting.socialAccounts.facebook.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.reddit.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-reddit-icon :url="templateSetting.socialAccounts.reddit.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.pinterest.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-pinterest-icon :url="templateSetting.socialAccounts.pinterest.url" />
              </div>
            </div>

            <div
              v-if="templateSetting.socialAccounts.tiktok.show"
              :class="{
                'tw-mr-auto': setting.layouts.alignment === 'left',
                'tw-m-auto': setting.layouts.alignment === 'center',
                'tw-ml-auto': setting.layouts.alignment === 'right'
              }"
            >
              <div class="tw-mt-2.5">
                <bz-tiktok-icon :url="templateSetting.socialAccounts.tiktok.url" />
              </div>
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
import BzYoutubeIcon from '../../components/social-icons/BzYoutubeIcon.vue'
import BzLinkedinIcon from '../../components/social-icons/BzLinkedinIcon.vue'
import BzInstagramIcon from '../../components/social-icons/BzInstagramIcon.vue'
import BzTwitterIcon from '../../components/social-icons/BzTwitterIcon.vue'
import BzFacebookIcon from '../../components/social-icons/BzFacebookIcon.vue'
import BzBizinaboxIcon from '../../components/social-icons/BzBizinaboxIcon.vue'
import BzRedditIcon from '../../components/social-icons/BzRedditIcon.vue'
import BzPinterestIcon from '../../components/social-icons/BzPinterestIcon.vue'
import BzTiktokIcon from '../../components/social-icons/BzTiktokIcon.vue'

export default {
  name: 'Social1',
  components: {
    BzTiktokIcon,
    BzPinterestIcon,
    BzRedditIcon,
    BzBizinaboxIcon,
    BzFacebookIcon,
    BzTwitterIcon,
    BzInstagramIcon,
    BzLinkedinIcon,
    BzYoutubeIcon,
    BzSetting,

    BzTitle,
    BzAlignment,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin],
  methods: {
    openSocialLinksForm() {
      eventBus.$emit('openSettingSlider', [2])
    }
  }
}
</script>
