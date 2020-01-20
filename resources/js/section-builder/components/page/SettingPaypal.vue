<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_paypal_body">
      <span class="m-accordion__item-title">Paypal</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_paypal_body" class="m-accordion__item-body collapse show">
      <div class="mb-2">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span>Paypal account</span>
          </div>
          <div class="col-7">
            <Input v-model="setting.paypal.paypalAccount" :height="30" />
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Currency</span>
          </div>
          <div class="col-7">
            <bz-select v-model="setting.paypal.currency" :match-width="false" :options="currencies" />
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label">Cart Button</span>
        <label class="custom_switch">
          <input v-model="setting.paypal.cartButton" type="checkbox" />
          <span />
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'
import currencies from '../../data/currencies'

export default {
  name: 'SettingPaypal',
  components: { BzSelect },
  extends: SettingBase,
  data() {
    return {
      currencies: currencies
    }
  }
}
</script>
