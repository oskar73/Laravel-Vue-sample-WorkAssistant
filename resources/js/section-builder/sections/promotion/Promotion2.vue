<template>
  <div class="bz-section-container bz-sec--promotion-2-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <bz-container>
        <bz-card :shadow="setting.layouts.shadow" :background-color="cardBackgroundColor" class="p-0">
          <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
            <div class="bz-col-md-5 py-0">
              <bz-image v-model="data.elements.image" resize-mode="full" />
            </div>
            <div class="bz-col-md-7 p-4">
              <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                <bz-icon v-model="data.elements.icon" :rounded="false" :border-radius="0" :full="true" :fill="false" :size="60" class="mb-5" />

                <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" size="32px" />

                <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
              </bz-alignment>

              <div class="bz-row">
                <div class="bz-col-lg-8">
                  <bz-setting @click="openPromotionModal">
                    <div class="bz-fl-email-address">
                      <input class="bz-be-input" placeholder="Enter your e-mail address" />
                    </div>
                  </bz-setting>
                </div>
                <div class="bz-col-lg-4">
                  <bz-button v-model="data.elements.button" :link="false" />
                </div>
              </div>

              <bz-text class="text-left" :value="data.promotion.permissionMessage" />
            </div>
          </div>
        </bz-card>
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
  name: 'Promotion2',
  components: { BzButton, BzSetting, BzTitle, BzIcon, BzAlignment, BzCard, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    cardBackgroundColor() {
      return this.backgroundColor.brighten(5)
    }
  },
  methods: {
    openPromotionModal() {
      this.$store.commit('openPromotion', {
        value: this.data.promotion,
        onChange: this.handlePromotionChange
      })
    },
    handlePromotionChange(promotion) {
      this.data.promoAction = promotion
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--promotion-2-root {
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
}
</style>
