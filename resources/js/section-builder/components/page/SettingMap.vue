<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_map_body">
      <span class="m-accordion__item-title">Map</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_map_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.map.hasOwnProperty('markers')" class="d-flex justify-content-between align-items-center mb-5">
        <button class="w-100 btn bz-btn-default mt-3" @click="openManageMarkersModal">Manage markers</button>
      </div>

      <div v-if="setting.map.hasOwnProperty('zoomLevel')" class="d-flex justify-content-between align-items-center mb-4">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Zoom level</span>
          </div>
          <div class="col-7">
            <slider v-model="setting.map.zoomLevel" :min="1" :max="20" thumb-label :step="1" />
          </div>
        </div>
      </div>

      <div v-if="setting.map.hasOwnProperty('type')" class="d-flex justify-content-between align-items-center mb-4">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Section size</span>
          </div>
          <div class="col-7">
            <bz-select v-model="setting.map.type" :options="setting.map.mapTypes || ['roadmap', 'satellite']" />
          </div>
        </div>
      </div>

      <div v-if="setting.map.hasOwnProperty('grayscale')" class="d-flex justify-content-between align-items-center mb-4">
        <span class="element_item_label">Grayscale</span>
        <label class="custom_switch">
          <input v-model="setting.map.grayscale" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.map.hasOwnProperty('zoomControl')" class="d-flex justify-content-between align-items-center mb-4">
        <span class="element_item_label">Zoom control</span>
        <label class="custom_switch">
          <input v-model="setting.map.zoomControl" type="checkbox" />
          <span />
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'
import Slider from '@vueform/slider'

export default {
  name: 'SettingMap',
  components: { BzSelect, Slider },
  extends: SettingBase,
  methods: {
    openManageMarkersModal() {
      this.$store.commit('openManageMarkers', {
        value: this.setting.map.markers,
        onChange: (markers) => {
          this.setting.map.markers = markers
          this.setting = window._copy(this.setting)
        }
      })
    }
  }
}
</script>
