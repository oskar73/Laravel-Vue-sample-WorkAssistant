<template>
  <div class="bz-section-container bz-sec--subscribe-1-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <bz-alignment alignment="center">
        <div class="bz-col-lx-4 bz-col-lg-6">
          <bz-card :shadow="setting.layouts.shadow" style="padding: 30px" :background-color="cardBackgroundColor">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-icon v-model="data.elements.icon" :rounded="false" :border-radius="0" :full="true" :fill="false" :size="40" />

              <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" size="32px" />

              <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
            </bz-alignment>

            <bz-setting @click="openSubscribeModal">
              <div class="bz-fl-email-address">
                <input class="bz-be-input" placeholder="Enter your e-mail address" />
              </div>
            </bz-setting>

            <div class="bz-fe-button">
              <bz-button v-model="data.elements.button" :link="false" />
            </div>

            <bz-text class="text-left" :value="data.subscribe.permissionMessage" />
          </bz-card>
        </div>
      </bz-alignment>
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
  name: 'Subscribe1',
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
.bz-sec--subscribe-1-root {
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
      height: 36px;
      text-align: center;
    }
  }
}
</style>
