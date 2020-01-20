<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_4_body">
      <span class="m-accordion__item-title">Visibility</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_4_body" class="m-accordion__item-body collapse show">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label">Visible</span>
        <label class="custom_switch">
          <input v-model="setting.visible" type="checkbox" />
          <span />
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase'
export default {
  name: 'SettingVisibility',
  extends: SettingBase
}
</script>

<style scoped></style>
