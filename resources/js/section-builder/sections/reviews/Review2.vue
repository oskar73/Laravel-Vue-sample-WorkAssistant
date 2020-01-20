<template>
  <div class="bz-section-container bz-sec--reviews-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="theme.primaryColor" />

          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :enable-sort="edit">
          <template slot-scope="{ item, index }">
            <bz-card :padding="25" class="position-relative">
              <div class="d-flex">
                <bz-image v-if="setting.listElements.image" v-model="item.image" :ratio="1" width="100px" rounded />
                <div class="d-flex flex-column justify-content-end pl-4">
                  <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="cardBackgroundColor" :mb="0" />

                  <bz-setting :index="index" @click="openStarForm">
                    <star-rating v-model:rating="item.review" :max-rating="item.review" :read-only="true" :show-rating="false" :star-size="18" active-color="#fd9d20" />

                    <div v-if="editing === index" v-click-outside="hideReviewsEditForm" class="bz-form-review-stars">
                      <div class="mr-2" style="font-size: 12px">Stars</div>
                      <slider v-model="item.review" thumb-label :min="0" :max="5" :step="1" />
                    </div>
                  </bz-setting>

                  <bz-text v-if="setting.listElements.date" v-model="item.date" :background-color="cardBackgroundColor" :mb="0" />
                </div>
              </div>

              <bz-text v-if="setting.listElements.title" v-model="item.description" class="my-3" :background-color="cardBackgroundColor" />

              <div class="bz-ee-button-container">
                <bz-button v-if="setting.listElements.buttons" v-model="item.button" :link="false" :rounded="true" />
              </div>
            </bz-card>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzButton from '../../components/section/BzButton.vue'
import StarRating from 'vue-star-rating'
import BzSetting from '../../components/section/BzSetting.vue'
import BzCard from '../../components/section/BzCard.vue'
import Slider from '@vueform/slider'

export default {
  name: 'Review2',
  components: {
    BzCard,
    BzSetting,
    BzButton,
    BzDivider,
    BzItems,

    BzTitle,
    BzAlignment,
    BzBackground,
    BzContainer,
    StarRating,
    Slider
  },
  mixins: [sectionMixin],
  data() {
    return {
      editing: false
    }
  },
  computed: {
    cardBackgroundColor() {
      return '#ffffff'
    }
  },
  methods: {
    openStarForm(index) {
      this.editing = index
    },
    hideReviewsEditForm() {
      this.editing = false
    }
  }
}
</script>

<style lang="scss">
.bz-sec--reviews-2-root {
  .bz-form-review-stars {
    position: absolute;
    display: flex;
    align-items: center;
    width: 200px;
    padding: 10px;
    box-shadow: 0 0 10px 2px #00000012;
    border: solid 1px #00000012;
    bottom: -50px;
    background-color: white;
    z-index: 100000;
  }

  .bz-ee-button-container {
    position: absolute;
    bottom: -20px;
    width: 100%;
    justify-content: center;
    align-items: center;
    display: flex;
    left: 0;
  }
}
</style>
