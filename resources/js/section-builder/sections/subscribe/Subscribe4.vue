<template>
  <div class="bz-section-container bz-sec--subscribe-4-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <bz-container>
        <div class="subscribe-form-container">
          <div class="subscribe-image">
            <bz-image v-model="data.elements.image" resize-mode="full" :disable-aspect-view="true" />
          </div>

          <div class="bz-col-12 bz-col-md-6 bz-col-lg-5 position-relative" style="z-index: 1">
            <bz-card :background-color="'#ffffff'" :padding="30">
              <bz-icon v-model="data.elements.icon" :rounded="false" :border-radius="0" :full="true" :background-color="'#ffffff'" :fill="false" :size="30" />

              <bz-alignment :alignment="setting.layouts.alignment" :stretch="false" class="my-3">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" size="32px" />

                <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="'#ffffff'" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="'#ffffff'" />
              </bz-alignment>

              <bz-setting @click="openSubscribeModal">
                <div class="bz-fl-email-address">
                  <input class="bz-be-input" placeholder="Enter your e-mail address" />
                </div>
              </bz-setting>

              <bz-button v-model="data.elements.button" :link="false" class="my-3" :width="'100%'" />

              <bz-text class="text-left" :value="data.subscribe.permissionMessage" :background-color="'#ffffff'" />
            </bz-card>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzIcon from '../../components/section/BzIcon.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzSetting from '../../components/section/BzSetting.vue'
import BzButton from '../../components/section/BzButton.vue'

export default {
  name: 'Subscribe4',
  components: { BzButton, BzSetting, BzTitle, BzIcon, BzAlignment, BzCard, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    cardBackgroundColor() {
      return this.backgroundColor.brighten(5)
    }
  },
  methods: {
    openSubscribeModal() {
      this.$store.commit('openSubscribe', {
        value: this.data.subscribe,
        onChange: this.handleSubscribeChange
      })
    },
    handleSubscribeChange(subscribe) {
      this.data.subscribe = subscribe
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--subscribe-4-root {
  .bz-fe-button {
    margin: 20px 0;
  }

  .bz-fl-email-address {
    width: 100%;
    background-color: #efefef;
    padding: 10px;
    border-radius: 4px;

    input {
      width: 100%;
      border: none;
      outline: none;
      font-family: inherit;
    }
  }

  .subscribe-form-container {
    position: relative;
    width: 100%;
    height: min-content;

    .subscribe-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }
  }
}
</style>
