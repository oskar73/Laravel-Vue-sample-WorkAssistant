<template>
  <div class="bz-section-container bz-sec--product-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <vue-carousel v-if="renderComponent" :items-to-show="3" :wrap-around="true" snap-align="start">
          <vue-slide v-for="(item, index) in data.items" :key="index">
            <div class="bz-fl-product-card">
              <div class="bz-ee-product-image">
                <bz-aspect-view>
                  <bz-image v-model="item.image" />
                </bz-aspect-view>
                <div class="product-button">
                  <bz-button v-model="item.button" :link="false" />
                </div>
              </div>
            </div>
          </vue-slide>
        </vue-carousel>
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
import rerenderMixin from '../../mixins/rerenderMixin'

export default {
  name: 'Ecommerce3',
  components: {
    BzAspectView,
    BzButton,
    BzBackground,
    BzContainer
  },
  mixins: [sectionMixin, rerenderMixin],
  watch: {
    data() {
      this.forceRerender()
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--product-2-root {
  .bz-fl-product-card {
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
      bottom: 10%;
    }
  }
}
</style>
