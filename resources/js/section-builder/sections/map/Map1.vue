<template>
  <div class="bz-section-container bz-sec--map-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <bz-container :full-size="setting.layouts.fullWidth">
        <bz-google-map
          :type="setting.map.type"
          :zoom="setting.map.zoomLevel"
          :height="600"
          :options="options"
          :grayscale="setting.map.grayscale"
          :location="setting.map.location"
        />
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'

export default {
  components: { BzBackground, BzContainer, BzGoogleMap },
  mixins: [sectionMixin],
  computed: {
    options() {
      return {
        zoomControl: this.setting.map.zoomControl
      }
    },
    grayscale() {
      if (this.setting.map.type === 'roadmap' || this.setting.map.type === 'terrain') {
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-sec--map-1-root {
}
</style>
