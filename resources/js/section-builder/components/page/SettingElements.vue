<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_1_body">
      <span class="m-accordion__item-title">Elements</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_1_body" class="m-accordion__item-body collapse show">
      <template v-for="(item, elKey) of setting.elements" :key="elKey">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="element_item_label" @dblclick="delete setting.elements[elKey]">
            {{ getSettingName(elKey) }}
          </span>
          <template v-if="typeof item === 'boolean'">
            <label class="custom_switch">
              <input v-model="setting.elements[elKey]" type="checkbox" />
              <span />
            </label>
          </template>
          <template v-else>
            <div style="width: 120px">
              <bz-input v-model="setting.elements[elKey]" />
            </div>
          </template>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import currencies from '../../data/currencies'
import BzInput from './BzInput.vue'

export default {
  name: 'SettingElements',
  components: { BzInput },
  extends: SettingBase,
  data() {
    return {
      currencies: currencies
    }
  }
}
</script>
