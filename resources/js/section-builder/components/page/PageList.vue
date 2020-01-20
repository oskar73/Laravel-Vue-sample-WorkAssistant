<template>
  <div class="p-3">
    <template v-for="(page, index) of allPages">
      <div v-if="page.type !== 'module' || (isWebsite && page.type !== 'new-page')" :key="index" class="page-item" :class="{ active: isActivePage(index) }">
        <div v-ripple class="page-name" @click="handlePageItemClick(index)">
          {{ page.name }}
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'

export default {
  name: 'PageList',
  mixins: [builderMixin],
  methods: {
    isActivePage(index) {
      if (this.activeSlider === 'theme' && this.appliedTo === 'page' && this.isNewPaletteMode) {
        return this.paletteAppliedPages.includes(index)
      } else {
        return this.indexOfActivePage === index
      }
    },
    handlePageItemClick(index) {
      if (this.appliedTo === 'page') {
        this.paletteAppliedPages.toggle(index)
      }

      if (this.indexOfActivePage !== index) {
        this.setActivePage({ index })
        // if (this.activeSlider === 'sections') {
        //   setTimeout(() => {
        //     this.prependSection(0)
        //   }, 400)
        // }

        // this.$dialog
        //   .confirm({
        //     title: 'Save Theme',
        //     description: 'Do you want to save current theme?'
        //   })
        //   .then((res) => {
        //     if (res) {
        //       eventBus.$emit('saveCurrentStoreTheme')
        //     }
        //   })
      }
    }
  }
}
</script>

<style lang="scss">
.page-item {
  border-radius: 4px;
  width: 100%;
  background-color: white;
  box-shadow: 0 0 2px 4px #00000012;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  margin-top: 10px;

  &:hover {
    background-color: #00000005;
  }

  &.active {
    outline: solid 2px var(--bz-edit-active);
  }

  .page-name {
    padding: 10px;
  }
}
</style>
