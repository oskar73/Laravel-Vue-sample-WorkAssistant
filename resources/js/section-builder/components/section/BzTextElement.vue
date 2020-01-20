<template>
  <div class="bz-el--text-element-root">
    <bz-alignment :alignment="alignment">
      <bz-title v-if="setting.title" v-model="data.title" :shadow="shadow" />

      <bz-subtitle v-if="setting.subtitle" v-model="data.subtitle" :shadow="shadow" />

      <bz-text v-if="setting.description" v-model="data.description" :shadow="shadow" />

      <bz-divider v-if="setting.dividerLine" :line="true" :width="dividerWidth" />
    </bz-alignment>
  </div>
</template>

<script>
import BzAlignment from './BzAlignment.vue'
import BzTitle from './BzTitle.vue'
import BzSubtitle from './BzSubtitle.vue'
import BzDivider from './BzDivider.vue'
import elementMixin from '../../mixins/elementMixin'

export default {
  components: { BzDivider, BzSubtitle, BzTitle, BzAlignment },
  mixins: [elementMixin],
  props: {
    setting: {
      type: Object,
      default: () => {
        return {}
      }
    },
    alignment: {
      type: String,
      default: 'left'
    },
    dividerWidth: {
      type: Number,
      default: 80
    },
    shadow: {
      type: Boolean,
      default: false
    }
  }
}
</script>
<style lang="scss">
.bz-el--text-element-root {
  width: 100%;
}
</style>
