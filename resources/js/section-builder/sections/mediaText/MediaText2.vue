<template>
  <div class="bz-section-container bz-sec--media-text-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-grid sm:tw-grid-cols-2 tw-gap-8">
          <div v-if="setting.elements.image">
            <div class="bz-ss-image">
              <bz-aspect-view :ratio="2 / 3">
                <bz-image v-model="data.elements.image" />
              </bz-aspect-view>
            </div>
          </div>
          <div>
            <div class="tw-w-full min-h-full tw-flex tw-flex-col tw-justify-center">
              <div v-if="setting.elements.dividerLine" class="tw-mb-10">
                <bz-divider :line="true" :thickness="4" width="20%" />
              </div>

              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
            </div>
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
import BzDivider from '../../components/section/BzDivider.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: { BzAspectView, BzButtonGroup, BzSubtitle, BzTitle, BzDivider, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

