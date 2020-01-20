<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_1_body">
      <span class="m-accordion__item-title">Portfolio Setting</span>
    </div>
    <div id="setting_accordion_item_1_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.portfolio.hasOwnProperty('category')" class="mb-2">
        <div class="element_item_label mb-2">Portfolio Category</div>
        <bz-select v-model="setting.portfolio.category" :options="portfolioCategories" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'

export default {
  name: 'SettingPortfolioModule',
  components: {
    BzSelect
  },
  extends: SettingBase,
  computed: {
    portfolioCategories() {
      const allCategories = [
        {
          label: 'All',
          value: ''
        }
      ]
      const categories = this.$store.state.modules.portfolio.categories
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
