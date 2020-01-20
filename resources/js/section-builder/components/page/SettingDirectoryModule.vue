<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_1_body">
      <span class="m-accordion__item-title">Directory Setting</span>
    </div>
    <div id="setting_accordion_item_1_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.listing.hasOwnProperty('category')" class="mb-2">
        <div class="element_item_label mb-2">Directory Category</div>
        <bz-select v-model="setting.listing.category" :options="listingCategories" />
      </div>
      <div v-if="setting.listing.hasOwnProperty('display')" class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label pr-5">Display</span>
        <bz-select v-model="setting.listing.display" :match-width="false" :options="[3, 5]" />
      </div>
      <!-- <div v-if="setting.listing.hasOwnProperty('listingCount')" class="d-flex justify-content-between align-items-center mb-2">
        <span class="element_item_label">Listing Count</span>
        <input v-model="setting.listing.listingCount" class="form-control" style="width: 126px" type="number" />
      </div> -->
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'

export default {
  name: 'SettingDirectoryModule',
  components: {
    BzSelect
  },
  extends: SettingBase,
  computed: {
    listingCategories() {
      const allCategories = [
        {
          label: 'All',
          value: ''
        }
      ]
      const categories = this.$store.state.modules.directory.categories
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
