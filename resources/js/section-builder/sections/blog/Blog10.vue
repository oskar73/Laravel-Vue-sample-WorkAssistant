<template>
  <div class="bz-section-container bz-sec--blog-10-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="d-flex align-items-center justify-content-center position-relative" style="min-height: 400px">
          <bz-aspect-view :ratio="1 / 2">
            <div class="blog-item" :style="{ backgroundImage: `url(${mainBlog.image.src})` }">
              <div class="blog-content">
                <bz-title v-model="mainBlog.title" :background-color="'#ffffff'" />
                <bz-text v-model="mainBlog.description" />
                <bz-editor-link :href="mainBlog.url">
                  <div class="mt-3">
                    <bz-button v-model="data.elements.button" />
                  </div>
                </bz-editor-link>
              </div>
            </div>
          </bz-aspect-view>
          <empty-blog v-if="showEmptySection" :edit="edit" />
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BlogBase from './BlogBase.vue'
import BzButton from '../../components/section/BzButton.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog10',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzButton,
    BzAspectView,
    BzContainer,
    BzBackground,
    BzTitle
  },
  extends: BlogBase,
  computed: {
    mainBlog() {
      return this.blogPosts[0]
    },
    mainBlogLink() {
      if (this.edit) {
        return 'javascript:void(0)'
      }
      const blogModule = this.$store.state.modules.blog
      if (!blogModule) {
        console.error('blog module does not exist')
        return '/'
      }

      const blogPage = blogModule.page
      if (!blogPage) {
        console.error('blog page does not exist')
        return '/'
      }
      return '/' + blogPage.url
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-10-root {
  @import 'blog';
  .blog-item {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-size: cover;
    background-repeat: no-repeat;
  }
  .blog-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #ffffffdf;
    padding: 20px;
    border-radius: 2px;
    * {
      color: black;
    }
  }
}
</style>
