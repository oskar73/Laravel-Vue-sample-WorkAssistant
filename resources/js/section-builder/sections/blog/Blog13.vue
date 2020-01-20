<template>
  <div class="bz-section-container bz-sec--blog-13-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" size="m">
      <bz-container>
        <div class="d-flex align-items-center justify-content-center position-relative" style="min-height: 400px">
          <bz-items v-model="blogPosts" :grid="true" :shadow="false" :spacing="false" :enable-sort="edit">
            <template v-slot="{ index }">
              <div class="blog-item">
                <bz-aspect-view>
                  <bz-image v-model="blogPosts[index].image" />
                </bz-aspect-view>
                <div class="blog-text">
                  <bz-text v-model="blogPosts[index].title" />
                  <bz-text v-model="blogPosts[index].description" />
                </div>
              </div>
            </template>
          </bz-items>
          <empty-blog v-if="showEmptySection" :edit="edit" />
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BlogBase from './BlogBase.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog13',
  components: { EmptyBlog, BzAspectView, BzItems, BzContainer, BzBackground },
  extends: BlogBase
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-13-root {
  .blog-item {
    position: relative;

    .blog-text {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 2;
      color: white;
      padding: 4px;
    }
  }
}
</style>
