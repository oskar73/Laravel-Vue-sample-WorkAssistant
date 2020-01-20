<template>
  <div class="bz-section-container bz-sec--portfolio-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :no-image="true">
      <bz-container>
        <div class="tw-gap-4 tw-grid tw-grid-cols-3 md:tw-grid-cols-4">
          <div class="tw-col-span-3">
            <bz-aspect-view>
              <img :src="getImgUrl(filteredItems[0])" alt="Main Image" class="tw-w-full tw-h-full tw-object-cover" />
            </bz-aspect-view>
          </div>
          <div class="tw-col-span-3 md:tw-col-span-1 tw-flex md:tw-flex-col tw-gap-4 md:tw-h-full tw-h-[100px]">
            <bz-aspect-view>
              <img v-if="filteredItems[1]" :src="getImgUrl(filteredItems[1])" alt="Image 1" class="tw-w-full md:tw-h-full tw-h-[100px] tw-object-cover" />
            </bz-aspect-view>
            <bz-aspect-view>
              <img v-if="filteredItems[2]" :src="getImgUrl(filteredItems[2])" alt="Image 2" class="tw-w-full md:tw-h-full tw-h-[100px] tw-object-cover" />
            </bz-aspect-view>
            <bz-aspect-view>
              <img v-if="filteredItems[3]" :src="getImgUrl(filteredItems[3])" alt="Image 3" class="tw-w-full md:tw-h-full tw-h-[100px] tw-object-cover" />
            </bz-aspect-view>
          </div>
          <template v-for="item in nextItems" :key="item.id">
            <bz-aspect-view>
              <img :src="getImgUrl(item)" alt="Image 3" class="tw-w-full md:tw-h-full tw-h-[100px] tw-object-cover" />
            </bz-aspect-view>
          </template>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

import portfolioMixin from './portfolioMixin'

export default {
  name: 'Portfolio2',
  components: {
    BzAspectView,
    BzContainer,
    BzBackground
  },
  mixins: [portfolioMixin],
  props: ['position'],
  data() {
    return {
      previewItemCount: 4
    }
  }
}
</script>
