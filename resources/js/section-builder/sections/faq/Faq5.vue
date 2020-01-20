<template>
  <div class="bz-section-container bz-sec--faq5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
        <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        <bz-items v-model="data.items" :cols="1" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-slide-up-down>
              <template v-slot:header>
                <div class="tw-px-2.5 tw-py-4 tw-rounded" :style="{ backgroundColor: secondaryColor }">
                  <bz-title v-model="item.question" :background-color="theme.primaryColor" />
                </div>
              </template>
              <template v-slot:content>
                <div class="tw-px-2.5">
                  <bz-text v-model="item.answer" />
                </div>
              </template>
            </bz-slide-up-down>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzSlideUpDown from '../../components/elements/BzSlideUpDown.vue'

export default {
  name: 'Faq5',
  components: { BzSlideUpDown, BzItems, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin],
  data() {
    return {
      active: true
    }
  }
}
</script>
