<template>
  <div class="bz-section-container bz-sec--blog-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-flex tw-items-center tw-justify-center tw-relative" style="min-height: 400px">
          <bz-items v-model="blogPosts" :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'" :enable-sort="edit"
            :show-add-item="true" :cols="setting.column" :shadow="setting.layouts.shadow">
            <template #default="{ item }">
              <template v-if="item">
                <div class="md:tw-grid tw-grid-cols-3">
                  <div v-if="setting.listElements.image" class="tw-w-full">
                    <bz-aspect-view :ratio="aspectRatio">
                      <bz-image v-model="item.image" resize-mode="full" />
                      <!-- <img class="w-100 blog-image" :src="item.image.src" alt="image" :style="imageStyle" /> -->
                    </bz-aspect-view>
                  </div>
                  <div class="md:tw-col-span-2 tw-w-full">
                    <div class="card-text-wrapper">
                      <bz-editor-link :href="item.url">
                        <bz-title v-if="setting.listElements.title" v-model="item.title"
                          :background-color="'#ffffff'" />
                      </bz-editor-link>
                      <bz-text v-if="setting.listElements.description" v-model="item.description" />
                    </div>
                  </div>
                </div>
                <bz-divider v-if="setting.listElements.lines" :line="true" />
              </template>
              <template v-else>
                <div class="tw-h-full tw-w-full blog-empty">
                  <bz-aspect-view :ratio="0.3">
                    <div class="tw-flex tw-flex-col">
                      <a :href="postBlogUrl" class="btn btn-info tw-text-white">Add Post</a>
                    </div>
                  </bz-aspect-view>
                </div>
              </template>
            </template>
          </bz-items>
          <empty-marketplace v-if="showEmptySection" :edit="edit" />
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import MarketPlaceBase from './MarketPlaceBase.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzItems from '../../components/section/BzItems.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyMarketPlace from './EmptyMarketPlace.vue'

export default {
  name: 'MarketPlace2',
  components: { EmptyMarketPlace, BzEditorLink, BzDivider, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MarketPlaceBase,
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-2-root {
  @import 'marketplace';
}
</style>
