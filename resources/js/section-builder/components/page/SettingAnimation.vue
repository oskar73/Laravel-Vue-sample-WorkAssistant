<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_9_body">
      <span class="m-accordion__item-title">Animation</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_9_body" class="m-accordion__item-body collapse show">
      <div v-if="true" class="mb-2">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Effect</span>
          </div>
          <div class="col-7">
            <bz-select v-model="setting.animationClass" :pre-selected="false" :match-width="false" :options="animationClasses" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase'
import BzSelect from './BzSelect'
export default {
  name: 'SettingAnimation',
  components: { BzSelect },
  extends: SettingBase,
  data() {
    return {
      animationClasses: [
        { label: 'Zoom In', value: 'animate_zoomIn' },
        { label: 'Zoom Out', value: 'animate_zoomOut' },
        { label: 'Slide In Down', value: 'animate_slideInDown' },
        { label: 'Slide Out Down', value: 'animate_slideOutDown' },
        { label: 'Bounce', value: 'animate_bounce' },
        { label: 'Rubber Band', value: 'animate_rubberBand' }
      ]
    }
  }
}
</script>
