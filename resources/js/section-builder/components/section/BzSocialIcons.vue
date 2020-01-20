<template>
  <div class="bz-el-social-icons-root">
    <bz-setting :wrap-content="wrapContent" class="tw-flex tw-items-center" @click="openSocialLinksForm">
      <div class="tw-flex tw-items-center tw-space-x-1 tw-flex-wrap">
        <bz-bizinabox-icon v-if="icons.bizinabox.show" class="social-item" :url="icons.bizinabox.url" :options="iconOptions('bizinabox')" />
        <bz-facebook-icon v-if="icons.facebook.show" class="social-item" :url="icons.facebook.url" :options="iconOptions('facebook')" />
        <bz-instagram-icon v-if="icons.instagram.show" class="social-item" :url="icons.instagram.url" :options="iconOptions('instagram')" />
        <bz-linkedin-icon v-if="icons.linkedin.show" class="social-item" :url="icons.linkedin.url" :options="iconOptions('linkedin')" />
        <bz-pinterest-icon v-if="icons.pinterest.show" class="social-item" :url="icons.pinterest.url" :options="iconOptions('pinterest')" />
        <bz-reddit-icon v-if="icons.reddit.show" class="social-item" :url="icons.reddit.url" :options="iconOptions('reddit')" />
        <bz-tiktok-icon v-if="icons.tiktok.show" class="social-item" :url="icons.tiktok.url" :options="iconOptions('tiktok')" />
        <bz-twitter-icon v-if="icons.twitter.show" class="social-item" :url="icons.twitter.url" :options="iconOptions('twitter')" />
        <bz-youtube-icon v-if="icons.youtube.show" class="social-item" :url="icons.youtube.url" :options="iconOptions('youtube')" />
      </div>
    </bz-setting>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzBizinaboxIcon from '../social-icons/BzBizinaboxIcon.vue'
import BzFacebookIcon from '../social-icons/BzFacebookIcon.vue'
import BzInstagramIcon from '../social-icons/BzInstagramIcon.vue'
import BzLinkedinIcon from '../social-icons/BzLinkedinIcon.vue'
import BzPinterestIcon from '../social-icons/BzPinterestIcon.vue'
import BzRedditIcon from '../social-icons/BzRedditIcon.vue'
import BzTiktokIcon from '../social-icons/BzTiktokIcon.vue'
import BzTwitterIcon from '../social-icons/BzTwitterIcon.vue'
import BzYoutubeIcon from '../social-icons/BzYoutubeIcon.vue'
import BzSetting from './BzSetting.vue'

export default {
  name: 'BzSocialIcons',
  components: { BzSetting, BzYoutubeIcon, BzTwitterIcon, BzTiktokIcon, BzRedditIcon, BzPinterestIcon, BzLinkedinIcon, BzInstagramIcon, BzFacebookIcon, BzBizinaboxIcon },
  mixins: [elementMixin],
  props: {
    wrapContent: {
      type: Boolean,
      default: true
    },
    size: {
      type: [Number, undefined],
      default: undefined
    },
    gap: {
      type: Number,
      default: 5
    }
  },
  computed: {
    icons() {
      return this.templateSetting.socialAccounts
    },
    socialItemStyle() {
      return {
        width: this.size + 'px',
        height: this.size + 'px',
        marginRight: this.gap + 'px',
        backgroundColor: this.backgroundColor,
        color: this.getColor()
      }
    }
  },
  methods: {
    openSocialLinksForm() {
      this.activePosition = 'footer'
      if (this.activeSlider !== 'settings') {
        this.$store.commit('setOpenSlider', { sliderName: 'settings', activeTab: 2 })
      }
    },
    iconOptions(iconName) {
      if (this.theme.socialIcon?.[this.sectionType]) {
        if (this.theme.socialIcon?.[this.sectionType].individual) {
          return this.theme.socialIcon?.[this.sectionType][iconName]
        } else {
          return this.theme.socialIcon?.[this.sectionType].group
        }
      }
      return undefined
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-el-social-icons-root {
  height: max-content;
  display: flex;
  align-items: center;

  .social-item {
    border-radius: 1000px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>
