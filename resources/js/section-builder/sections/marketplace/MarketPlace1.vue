<template>
  <div class="bz-section-container bz-sec--blog-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-flex tw-items-center tw-justify-center tw-relative" style="min-height: 400px">
          <bz-items v-model="blogPosts" :edit-mode="isTemplate"
            :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'" :enable-sort="edit" :show-add-item="true"
            :cols="setting.column" :shadow="setting.layouts.shadow">
            <template #default="{ item }">
              <template v-if="item">
                <bz-aspect-view :ratio="aspectRatio">
                  <bz-image v-model="item.image" resize-mode="full" />
                  <!-- <img class="w-100 blog-image" :src="mainBlogImage.src" alt="image" :style="imageStyle" /> -->
                </bz-aspect-view>
                <div class="card-text-wrapper">
                  <bz-editor-link :href="item.url">
                    <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                    <!-- <h3 v-if="setting.listElements.title">{{ blogPosts.image.src }}</h3> -->
                  </bz-editor-link>
                  <bz-text v-if="setting.listElements.description" v-model="item.description" />
                </div>
              </template>
            </template>
          </bz-items>
          <empty-marketplace v-if="showEmptySection" :edit="edit" />
        </div>
        <div class="tw-flex tw-justify-center">
          <button v-if="isBuilder" class="tw-bg-blue-500 tw-text-white tw-rounded tw-shadow tw-px-4 tw-py-1"
            @click.prevent="handleAddItem()">Add Blog</button>
          <a @click="goToPage()"
            class="cursor-pointer tw-bg-blue-500 tw-text-white tw-rounded tw-shadow tw-px-4 tw-py-1"
            v-if="blogPosts.length && !isBuilder">View</a>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import MarketPlaceBase from './MarketPlaceBase.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyMarketPlace from './EmptyMarketPlace.vue'

export default {
  name: 'MarketPlace1',
  components: { EmptyMarketPlace, BzEditorLink, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MarketPlaceBase
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-1-root {
  @import 'marketplace';
}
</style>
