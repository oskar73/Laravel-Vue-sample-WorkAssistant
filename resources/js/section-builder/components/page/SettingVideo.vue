<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_paypal_body">
      <span class="m-accordion__item-title">Video</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_paypal_body" class="m-accordion__item-body collapse show">
      <div class="mb-2">
        <button class="w-100 btn bz-btn-default mt-1" @click="openVideoSelector">Select Video</button>
        <div class="row mt-2">
          <div class="col-6">Auto Play</div>
          <div class="col-6">
            <bz-switch v-model="setting.video.autoplay" />
          </div>

          <div class="col-6">Loop</div>
          <div class="col-6">
            <bz-switch v-model="setting.video.loop" />
          </div>

          <div class="col-6">Mute</div>
          <div class="col-6">
            <bz-switch v-model="setting.video.mute" />
          </div>

          <div class="col-6">Controls</div>
          <div class="col-6">
            <bz-switch v-model="setting.video.controls" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSwitch from './BzSwitch.vue'
export default {
  name: 'SettingVideo',
  components: { BzSwitch },
  extends: SettingBase,
  methods: {
    openVideoSelector() {
      this.$store.commit('openYoutubeVideo', {
        onChange: (value) => {
          this.setting.video.source = value.source
        }
      })
    }
  }
}
</script>
