<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_6_body">
      <span class="m-accordion__item-title">Card</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_6_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.card.hasOwnProperty('opacity')" class="mb-2">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Opacity</span>
          </div>
          <div class="col-7">
            <slider v-model="setting.card.opacity" :min="10" :max="100" thumb-label :step="1" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import Slider from '@vueform/slider'

export default {
  name: 'SettingCard',
  components: [Slider],
  extends: SettingBase
}
</script>
