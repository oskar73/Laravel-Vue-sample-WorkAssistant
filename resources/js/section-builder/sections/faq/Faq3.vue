<template>
  <div class="bz-section-container bz-sec--faq3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

        <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="tw-p-4" :style="{ backgroundColor: secondaryColor }">
              <bz-title v-model="item.question" :text-color="theme.primaryColor" size="20px" />
            </div>
            <div class="tw-p-4 tw-rounded">
              <bz-text v-model="item.answer" class="tw-text-black" />
            </div>
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

export default {
  components: { BzItems, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--faq3-root {
  .faq-question-wrapper {
    padding: 15px;
  }
  .faq-answer-wrapper {
    padding: 15px;
    border-radius: 2px;
  }
}
</style>
