<template>
  <div class="bz-section-container bz-sec--footer-7-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="sm:tw-grid md:tw-grid-cols-3 tw-grid-cols-2">
          <div>
            <bz-logo :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoSizeChange" />
            <div v-if="activePage?.data" class="my-3">
              <bz-title v-model="activePage.data.title" />
            </div>
          </div>
          <template v-if="data.items">
            <div>
              <bz-title v-model="data.items[0].title" />
              <div v-for="(link, index) of data.items[0].links" :key="index" class="mt-2 tw-h-fit">
                <bz-link :link="link.link">
                  <bz-text v-model="link.label" :link="link.link" :with-link="true" @changeLink="link.link = $event" />
                </bz-link>
              </div>
            </div>
            <div>
              <bz-title v-model="data.items[1].title" />
              <div v-for="(link, index) of data.items[1].links" :key="index" class="mt-2 tw-h-fit">
                <bz-link :link="link.link">
                  <bz-text v-model="link.label" :link="link.link" :with-link="true" @changeLink="link.link = $event" />
                </bz-link>
              </div>
            </div>
          </template>
        </div>
        <bz-social-icons />
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BaseFooter from './BaseFooter.vue'

import BzLink from '../../components/section/BzLink.vue'
import BzPageLink from '../../components/page/BzPageLink.vue'
import PageLink from '../../components/elements/PageLink.vue'

export default defineComponent({
  name: 'Footer7',
  components: {
    PageLink,
    BzPageLink,
    BzLink,

    BzLogo,
    BzSocialIcons,
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
.bz-sec--footer-7-root {
  @import 'style';
  .justify-space-between {
    justify-content: space-between;
  }
}
</style>
