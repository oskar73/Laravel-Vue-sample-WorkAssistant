<template>
  <div class="bz-section-container bz-sec--iframe-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <div class="m-auto" style="width: fit-content">
        <iframe :src="setting.iframe.url" :style="iframeStyle"></iframe>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'

export default {
  components: { BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    iframeStyle() {
      if (this.setting.layouts.fullPage) {
        return {
          width: this.sectionWidth + 'px',
          height: '100vh'
        }
      } else {
        return {
          width: this.setting.elements.width + 'px',
          height: this.setting.elements.height + 'px'
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-sec--iframe-1-root {
  iframe {
    border: none;
    outline: none;
  }
}
</style>
