<template>
  <div class="bz-section-container bz-sec--product-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="row">
          <div class="bz-col-lg-4">
            <div class="bz-fl-product-card">
              <div class="bz-ee-product-image">
                <bz-aspect-view>
                  <bz-image v-model="data.items[0].image" />
                </bz-aspect-view>
              </div>

              <div class="product-title">
                <bz-title v-if="setting.listElements.title" v-model="data.items[0].title" />
              </div>

              <div class="bz-ee-product-content" :style="{ backgroundColor: headingColor }">
                <bz-title v-model="data.items[0].subtitle" />
                <div class="mt-2">
                  <bz-text v-model="data.items[0].description" />
                </div>
              </div>
            </div>
          </div>

          <div class="bz-col-lg-4">
            <div class="bz-fl-product-card">
              <div class="bz-ee-product-image">
                <bz-aspect-view>
                  <bz-image v-model="data.items[1].image" />
                </bz-aspect-view>
              </div>

              <div class="product-title">
                <bz-title v-if="setting.listElements.title" v-model="data.items[1].title" />
              </div>

              <div class="bz-ee-product-content" :style="{ backgroundColor: boxColor }">
                <bz-title v-model="data.items[1].subtitle" />
                <div class="mt-2">
                  <bz-text v-model="data.items[1].description" />
                </div>
              </div>
            </div>
          </div>

          <div class="bz-col-lg-4">
            <div class="bz-fl-product-card">
              <div class="bz-ee-product-image">
                <bz-aspect-view>
                  <bz-image v-model="data.items[2].image" />
                </bz-aspect-view>
              </div>

              <div class="product-title">
                <bz-title v-if="setting.listElements.title" v-model="data.items[2].title" />
              </div>

              <div class="bz-ee-product-content" :style="{ backgroundColor: secondaryColor }">
                <bz-title v-model="data.items[2].subtitle" />
                <div class="mt-2">
                  <bz-text v-model="data.items[2].description" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'

import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  name: 'Ecommerce1',
  components: {
    BzAspectView,
    BzBackground,
    BzContainer,
    BzTitle
  },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--product-1-root {
  .bz-fl-product-card {
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;

    .bz-ee-product-image {
      position: relative;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .product-button {
      position: absolute;
      z-index: 2;
    }

    .product-title {
      text-align: center;
      margin: 20px 0;
      width: 100%;
    }

    .bz-ee-product-content {
      width: 100%;
      text-align: center;
      padding: 10px;
      border: solid 5px white;
    }
  }
}
</style>
