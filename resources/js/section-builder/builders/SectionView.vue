<template>
  <div class="bz-section-root">
    <bz-layout :data="sectionData.layout" />
  </div>
</template>

<script>
import BzLayout from './components/BzLayout'
export default {
  name: 'SectionView',
  components: { BzLayout },
  data() {
    return {
      sectionData: {
        layout: {
          name: 'layout',
          data: {
            style: {
              direction: 'column'
            },
            children: [
              {
                name: 'Layout',
                data: {
                  children: []
                }
              },
              {
                name: 'Layout',
                data: {
                  children: []
                }
              },
              {
                name: 'Layout',
                data: {
                  children: []
                }
              }
            ]
          }
        },
        data: {},
        background: {}
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-section-root {
  padding: 10px;
}
</style>
