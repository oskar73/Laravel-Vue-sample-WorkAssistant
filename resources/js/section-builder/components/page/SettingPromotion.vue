<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_8_body">
      <span class="m-accordion__item-title">Promotion</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_8_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.promotion.hasOwnProperty('promotionalCode')" class="mb-2">
        <label>Promotional code</label>
        <bz-input v-model="setting.promotion.promotionalCode" :height="40" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzInput from './BzInput.vue'
export default {
  name: 'SettingPromotion',
  components: { BzInput },
  extends: SettingBase
}
</script>
