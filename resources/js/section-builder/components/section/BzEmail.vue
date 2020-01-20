<template>
  <div class="bz-el--business-email-root" :style="{ fontFamily: theme.paragraphFont, color: color }" :class="{ edit }">
    <bz-setting :edit="edit" @click="handleClick">
      <div class="text-email">{{ business.contact.email }}</div>
    </bz-setting>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzSetting from './BzSetting.vue'
export default {
  name: 'BzEmail',
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
    handleClick() {
      this.$store.commit('setOpenSlider', { sliderName: 'settings', activeTab: 1, activeSubTab: 'tab-contact' })
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-el--business-email-root {
  position: relative;
  border: solid 2px transparent;

  .text-email {
    text-decoration: underline;
    height: 2rem;
    display: flex;
    align-items: center;
  }
}
</style>
