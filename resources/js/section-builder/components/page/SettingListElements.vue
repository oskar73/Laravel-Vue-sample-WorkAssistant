<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_4_body">
      <span class="m-accordion__item-title">List Elements</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_4_body" class="m-accordion__item-body collapse show">
      <template v-for="(listItem, listKey) of setting.listElements" :key="listKey">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="element_item_label" @dblclick="delete setting.listElements[elKey]">{{ getSettingName(listKey) }}</span>
          <label class="custom_switch">
            <input v-model="setting.listElements[listKey]" type="checkbox" />
            <span />
          </label>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
export default {
  name: 'SettingListElements',
  extends: SettingBase
}
</script>
