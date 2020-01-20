<template>
  <vue-final-modal v-model="showModal" :classes="['icon-selector-modal']" :max-width="800" :max-height="800" @closed="closeModal">
    <div class="tw-h-full tw-w-full tw-flex tw-flex-col">
      <div class="modal-header p-3" style="background-color: rgb(246, 246, 246)">
        <h5 class="tw-text-2xl">Icons</h5>
        <div class="d-flex align-items-center">
          <div class="search-box-wrapper">
            <input v-model="searchText" class="form-control" placeholder="Search Icon" type="text" />
          </div>
          <div class="d-flex align-items-center justify-content-center ml-3" @click="closeModal">
            <i class="mdi mdi-close"></i>
          </div>
        </div>
      </div>
      <div class="p-4 tw-overflow-y-auto tw-flex-1">
        <div
          class="tw-w-max tw-border tw-rounded tw-cursor-pointer hover:tw-bg-white tw-ml-1 tw-mt-1 tw-bg-gray-100 tw-flex tw-justify-center tw-items-center tw-shrink-0 tw-p-2"
          @click="select('')"
        >
          No Icon
        </div>
        <template v-for="(group, groupName) in filteredIcons" :key="groupName">
          <div v-if="group.length > 0" class="flex-row">
            <h6 class="tw-text-lg my-3 text-capitalize">{{ groupName }}</h6>
            <div class="tw-flex tw-flex-wrap">
              <template v-for="icon in group" :key="icon">
                <div
                  class="tw-w-10 tw-h-10 tw-border tw-rounded tw-cursor-pointer hover:tw-bg-white tw-ml-1 tw-mt-1 tw-bg-gray-100 tw-flex tw-justify-center tw-items-center tw-shrink-0"
                  @click="select(icon)"
                >
                  <i :class="icon"></i>
                </div>
              </template>
            </div>
          </div>
        </template>
      </div>
    </div>
  </vue-final-modal>
</template>

<script>
import { VueFinalModal } from 'vue-final-modal'
import icons from '../../data/icons'
import { mapMutations } from 'vuex'

export default {
  name: 'IconSelectorModal',
  components: { VueFinalModal },
  data() {
    return {
      searchText: '',
      iconNames: icons,
      filteredIcons: icons,
      showModal: true
    }
  },
  watch: {
    searchText(value) {
      if (value) {
        const icons = {}
        for (const key of Object.keys(this.iconNames)) {
          const group = this.iconNames[key].filter((icon) => icon.includes(value.toLowerCase()))
          if (group.length > 0) {
            icons[key] = group
          }
        }
        this.filteredIcons = icons
      } else {
        this.filteredIcons = this.iconNames
      }
    }
  },
  methods: {
    select(selection) {
      this.$store.state.modals.basic.onChange(selection)
      this.closeModal()
    },
    ...mapMutations(['closeModal'])
  }
}
</script>
<style>
.icon-selector-modal {
  width: 90%;
  height: 90vh;
  margin: auto;
}
</style>
