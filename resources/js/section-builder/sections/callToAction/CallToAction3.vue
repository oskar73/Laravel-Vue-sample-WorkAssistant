<template>
  <div class="bz-section-container bz-sec-call-to-action-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :background-color="'#ffffff'" :padding="0">
          <div class="col-12">
            <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
              <div class="col-lg-6 p-0">
                <bz-image v-model="data.elements.image" :ratio="2 / 3" />
              </div>
              <div class="col-lg-6 d-flex align-items-center">
                <div class="content" :class="{ [setting.layouts.alignment]: true }">
                  <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="'#ffffff'" />

                  <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="'#ffffff'" />

                  <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="'#ffffff'" />

                  <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" :background-color="'#ffffff'" />
                </div>
              </div>
            </div>
          </div>
        </bz-card>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzButton from '../../components/section/BzButton.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzCard from '../../components/section/BzCard.vue'
export default {
  components: {
    BzCard,
    BzButtonGroup,
    BzButton,

    BzSubtitle,
    BzTitle,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec-call-to-action-3-root {
  .content {
    display: flex;
    flex-direction: column;
    justify-content: center;

    &.left {
      align-items: flex-start;
      text-align: left;
    }

    &.center {
      align-items: center;
      text-align: center;
    }

    &.right {
      text-align: right;
      align-items: flex-end;
    }
  }
}
</style>
