<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_1_body">
      <span class="m-accordion__item-title">Product Setting</span>
    </div>
    <div id="setting_accordion_item_1_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.product.hasOwnProperty('category')" class="mb-2">
        <div class="element_item_label mb-2">Product Category</div>
        <bz-select v-model="setting.product.category" :options="productCategories" />
      </div>
      <div v-if="setting.product.hasOwnProperty('display')" class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label pr-5">Display</span>
        <bz-select v-model="setting.product.display" :match-width="false" :options="[3, 5]" />
      </div>
      <div v-if="setting.product.hasOwnProperty('productCount')" class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label">Product Count</span>
        <input v-model="setting.product.productCount" class="form-control" style="width: 126px" type="number" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'

export default {
  name: 'SettingProductModule',
  components: {
    BzSelect
  },
  extends: SettingBase,
  computed: {
    productCategories() {
      const allCategories = [
        {
          label: 'All',
          value: ''
        }
      ]
      const categories = this.$store.state.modules.ecommerce?.categories
      if (categories) {
        for (const category of categories) {
          allCategories.push({
            label: category.name,
            value: category.id
          })
        }
      }
      return allCategories
    }
  }
}
</script>
