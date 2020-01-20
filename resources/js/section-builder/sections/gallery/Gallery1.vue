<template>
  <div class="bz-section-container gallery1" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

        <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
      </bz-container>
      <bz-container :full-size="fullSize">
        <div class="tw-w-full">
          <bz-items v-model="data.items" :cols="setting.column" :spacing="setting.layouts.spacing" :enable-sort="edit">
            <template v-slot="{ item }">
              <bz-aspect-view>
                <bz-image v-model="item.image" :circle="setting.layouts.shape === 'circle'" />
              </bz-aspect-view>
              <div v-if="setting.listElements.title" class="tw-p-[5px]" :style="{ backgroundColor: secondaryColor }">
                <bz-title v-if="setting.listElements.title" v-model="item.title" class="tw-text-center tw-p-4" :mb="0" />
              </div>
            </template>
          </bz-items>
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: {
    BzAspectView,
    BzItems,

    BzSubtitle,
    BzTitle,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin],
  methods: {
    handleImageItemChange(index, value) {
      this.item = value
      this.data = window._copy(this.data)
    }
  }
}
</script>
