<template>
  <div class="zb_el-address-root" :style="{ fontFamily: theme.paragraphFont, color: color }" :class="{ edit }">
    <bz-setting :edit="edit" @click="openSetting">
      <div>{{ business.address }}</div>
      <div>{{ business.city }}, {{ business.state }}</div>
      <div>{{ business.country }}</div>
    </bz-setting>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzSetting from './BzSetting.vue'

export default {
  name: 'BzAddress',
  components: { BzSetting },
  mixins: [elementMixin],
  props: {
    location: {
      type: String,
      default: ''
    }
  },
  computed: {
    business() {
      let selectedBusiness = this.templateSetting.businesses.find((business) => business.companyName === this.location)

      if (typeof selectedBusiness === 'undefined') {
        selectedBusiness = this.templateSetting.businesses[0]
      }

      return selectedBusiness
    }
  },
  methods: {
    openSetting() {
      this.$store.commit('setOpenSlider', { sliderName: 'settings', activeTab: 1, activeSubTab: 'tab-address' })
    }
  }
}
</script>
