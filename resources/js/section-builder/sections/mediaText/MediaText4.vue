<template>
  <div class="bz-section-container bz-sec--media-text-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="md:tw-relative md:tw-w-full">
          <div class="tw-grid md:tw-grid-cols-2">
            <div class="tw-h-full max-md:tw-min-h-[240px] tw-min-h-[200px]">
              <bz-image v-if="setting.elements.image" v-model="data.elements.image" :ratio="1" />
            </div>
            <div>
              <div class="md:tw-absolute md:tw-w-[70%] md:tw-z-[1] md:tw-m-auto md:tw-mr-0 md:tw-h-max tw-top-0 tw-right-0 tw-left-0 tw-bottom-0">
                <bz-card :padding="30" :bg-color="secondaryColor">
                  <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

                  <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

                  <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

                  <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
                </bz-card>
              </div>
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
import BzTitle from '../../components/section/BzTitle.vue'
import BzButtonGroup from '../../components/section/BzButton.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzCard from '../../components/section/BzCard.vue'

export default {
  components: { BzCard, BzSubtitle, BzButtonGroup, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

