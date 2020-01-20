<template>
  <main class="w-100 h-100 tw-relative">
    <loader v-if="loadingData" />
    <template v-else>
      <modals />
      <div class="edit_page_container">
        <div class="content_body">
          <nav-bar />
          <slider-bar />
          <!--                    <router-view v-if="renderComponent"></router-view>-->
          <iframe-content v-if="renderComponent" />
          <setting-panel v-if="isOpenSettingPanel" />
        </div>
        <div id="bz-element-editor"></div>
      </div>
    </template>
  </main>
</template>

<script>
import NavBar from '@/section-builder/components/page/NavBar.vue'
import SliderBar from '@/section-builder/components/page/SliderBar.vue'
import SettingPanel from '@/section-builder/components/page/SettingPanel.vue'
import Modals from '@/section-builder/components/modals/Modals.vue'
import builderMixin from '@/section-builder/mixins/builderMixin'
import Loader from '@/public/Loader.vue'
import eventBus from '@/public/eventBus'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'
import IframeContent from '@/section-builder/components/page/IframeContent.vue'

export default {
  components: {
    IframeContent,
    Loader,
    Modals,
    SettingPanel,
    SliderBar,
    NavBar
  },
  mixins: [builderMixin, rerenderMixin],
  async created() {
    this.$store.commit('enableEdit')
    // get template data
    this.$store.commit('setTemplate', { websiteId: this.$config.websiteId })

    await this.$store.dispatch('fetchUserTemplates')
  },
  mounted() {
    eventBus.$on('template:update', () => {
      this.loadingData = false
    })
    // if (window.appEnv !== 'local') {
    //   window.onbeforeunload = function () {
    //     return 'Do you want to leave?'
    //   }
    // }

    // window resize handler
    let timer
    window.addEventListener('resize', () => {
      if (timer) {
        clearTimeout(timer)
      }
      timer = setTimeout(async () => {
        await this.forceRerender()
      }, 300)
    })

    eventBus.$on('ReloadPageContent', async () => {
      await this.forceRerender()
    })
  }
}
</script>
<style lang="scss">
.edit_page_container {
  position: relative;
  background-color: rgb(217, 222, 227);
  height: 100%;
  width: 100%;
}
</style>
