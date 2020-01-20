<template>
  <div class="bz-section-container bz-sec--faq-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="tw-p-4 tw-relative" :style="{ '--faq-question-wrapper-color': secondaryColor }">
              <bz-text v-model="item.question" size="20px" />
            </div>
            <div class="tw-p-4 tw-bg-white">
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
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzItems from '../../components/section/BzItems.vue'

export default {
  components: { BzItems, BzTitle, BzContainer, BzBackground, BzAlignment },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--faq-root {
  .faq-question-wrapper {
    background-color: var(--faq-question-wrapper-color);

    &::after {
      content: '';
      position: absolute;
      width: 15px;
      height: 15px;
      transform: rotate(45deg);
      background-color: var(--faq-question-wrapper-color);
      bottom: -8px;
      left: 15%;
    }
  }
}
</style>
