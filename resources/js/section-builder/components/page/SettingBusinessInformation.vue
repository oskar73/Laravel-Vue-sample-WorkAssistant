<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_5_body">
      <span class="m-accordion__item-title">Business information</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_5_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.businessInformation.hasOwnProperty('location')" class="mb-2">
        <div class="row">
          <div class="col-5 d-flex align-items-center">
            <span class="element_item_label">Location</span>
          </div>
          <div class="col-7">
            <bz-select v-model="setting.businessInformation.location" :match-width="false" :options="businessLocations" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'
export default {
  name: 'SettingBusinessInformation',
  components: { BzSelect },
  extends: SettingBase,
  computed: {
    businessLocations() {
      return this.templateSetting.businesses.map((business) => {
        return business.companyName
      })
    }
  }
}
</script>
