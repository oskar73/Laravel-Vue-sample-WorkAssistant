<script>
import { defineComponent } from 'vue'
import HeaderPage from '@/svg-editor/components/layouts/HeaderPage.vue'
import SvgEditor from '@/svg-editor/components/SvgEditor.vue'
import FooterPage from '@/svg-editor/components/layouts/FooterPage.vue'
import appMixin from '@/svg-editor/mixins/app-mixin'
import Loader from '@/public/Loader.vue'
import { getDesignData } from '@/svg-editor/api'
import LeftSideBar from '@/svg-editor/components/LeftSideBar.vue'
import RightSideBar from '@/svg-editor/components/RightSideBar.vue'
import ChooseDesign from '@/svg-editor/components/ChooseDesign.vue'

export default defineComponent({
  name: 'DesignEditor',
  components: { ChooseDesign, RightSideBar, LeftSideBar, Loader, FooterPage, SvgEditor, HeaderPage },
  mixins: [appMixin],
  mounted() {
    if (this.liveView) {
      this.loadingSvgData = false
    } else {
      this.loadingSvgData = true
      getDesignData(window.svgData.hash).then((data) => {
        console.info('getDesignData', data)
        if (data) {
          this.designData = data

          // Append fonts
          const link = document.createElement('link')
          link.setAttribute('rel', 'stylesheet')
          link.setAttribute('type', 'text/css')
          link.setAttribute('href', data.fontUrl)
          document.head.appendChild(link)

          this.loadingSvgData = false
        } else {
          window.location.href = this.route('graphics.index')
        }
      })
    }
  }
})
</script>

<template>
  <div v-if="loadingSvgData" class="tw-h-screen tw-w-screen tw-fixed tw-z-50">
    <loader />
  </div>
  <template v-else>
    <header-page />
    <footer-page />
    <choose-design v-if="liveView" />
    <template v-else>
      <svg-editor />
      <right-side-bar />
      <left-side-bar />
    </template>
  </template>
</template>

<style scoped lang="scss"></style>
