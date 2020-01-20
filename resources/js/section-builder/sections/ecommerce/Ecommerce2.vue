<template>
  <div class="bz-section-container bz-sec--product-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="row">
          <div class="bz-col-lg-4">
            <div class="bz-fl-product-card">
              <div class="bz-ee-product-image">
                <bz-aspect-view>
                  <bz-image v-model="data.items[0].image" />
                </bz-aspect-view>
                <div class="product-button">
                  <bz-button v-model="data.items[0].button" :link="false" />
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
                <div class="product-button">
                  <bz-button v-model="data.items[1].button" :link="false" />
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
                <div class="product-button">
                  <bz-button v-model="data.items[2].button" :link="false" />
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
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzButton from '../../components/section/BzButton.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  name: 'Ecommerce2',
  components: {
    BzAspectView,
    BzButton,
    BzBackground,
    BzContainer
  },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--product-2-root {
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
  }
}
</style>
