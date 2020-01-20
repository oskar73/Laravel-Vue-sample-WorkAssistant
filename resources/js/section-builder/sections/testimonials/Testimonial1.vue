<template>
  <div class="bz-section-container bz-sec--testimonials-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-alignment :alignment="setting.layouts.alignment">
        <bz-title v-model="data.elements.title" :text-color="theme.primaryColor" />

        <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
      </bz-alignment>

      <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :enable-sort="edit">
        <template slot-scope="{ item, index }">
          <div class="bz-testimonial-item">
            <div class="bz-testimonial-icon"></div>
          </div>
        </template>
      </bz-items>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzItems from '../../components/section/BzItems.vue'

export default {
  components: { BzItems, BzTitle, BzAlignment, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--testimonials-1-root {
  .bz-testimonial-item {
    display: flex;

    .bz-testimonial-icon {
      width: 80px;
      padding-right: 20px;
      border-right: solid 2px #000000;
    }

    .bz-testimonial-body {
      width: calc(100% - 80px);
      padding: 10px;
    }
  }
}
</style>
