<template>
  <section class="bz-section-container bz-sec--address1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-w-full tw-h-full tw-flex tw-justify-around tw-flex-col lg:tw-p-8">
          <bz-alignment :alignment="setting.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :shadow="setting.elements.shadow" />
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :shadow="setting.elements.shadow" />
            <bz-divider v-if="setting.elements.dividerLine" :line="true" :width="setting.elements.dividerWidth" />
          </bz-alignment>
          <div class="lg:tw-grid tw-grid-cols-2 tw-gap-6">
            <div>
              <bz-business-hours :location="setting.businessInformation.location" :design="4" />
            </div>
            <div>
              <div class="tw-m-8" :class="{ [setting.layouts.alignment]: true }">
                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
              </div>
            </div>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </section>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzBusinessHours from '../../components/section/BzBusinessHours.vue'

import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  name: 'BusinessHours6',
  components: {
    BzDivider,
    BzSubtitle,
    BzTitle,
    BzAlignment,
    BzContainer,

    BzBusinessHours,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec--address1-root {
  .title-sub-title-wrapper,
  .text-wrapper {
    &.left {
      text-align: left;
    }
    &.right {
      text-align: right;
    }
    &.center {
      text-align: center;
    }
  }
}
</style>
