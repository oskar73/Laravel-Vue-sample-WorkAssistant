<template>
  <div class="bz-section-container bz-sec--cover-image-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize" :no-image="true">
      <bz-container>
        <div class="lg:tw-grid tw-grid-cols-2 tw-space-y-6">
          <div>
            <div class="tw-flex tw-flex-col tw-h-full tw-justify-center">
              <div v-if="data.elements.image" style="max-width: 500px">
                <bz-image v-model="data.elements.image" :no-zoom="true" />
              </div>

              <div>
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
                <div style="margin-top: 10px">
                  <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
                </div>
                <div style="margin-top: 10px">
                  <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
                </div>

                <div style="margin-top: 20px">
                  <bz-button v-if="setting.elements.button" v-model="data.elements.button" />
                </div>
              </div>
            </div>
          </div>
          <div>
            <bz-aspect-view v-if="data.meta?.image_1">
              <bz-image v-model="data.meta.image_1" />
            </bz-aspect-view>
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
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

import BzButton from '../../components/section/BzButton.vue'

export default {
  components: { BzButton, BzAspectView, BzButtonGroup, BzDivider, BzTitle, BzSubtitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-6-root {
}
</style>
