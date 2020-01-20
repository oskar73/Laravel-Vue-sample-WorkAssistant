<template>
  <div v-if="isWebsite" class="blog-empty-section d-flex flex-column align-items-center justify-content-center">
    <template v-if="edit">
      <p class="text-white message">Sample Posts (will not show on site), Please click here to add your own posts.</p>
      <a :href="postBlogUrl" class="btn btn-primary text-white mt-3"> Manage Blog Posts </a>
    </template>
    <div v-else class="no-blog-posts">There are no published blog posts yet.</div>
  </div>
</template>

<script>
import appMixin from '../../mixins/appMixin'

export default {
  name: 'EmptyBlog',
  mixins: [appMixin],
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    postBlogUrl() {
      return this.template.webUrl + '/admin/blog/post/create'
    }
  }
}
</script>

<style lang="scss" scoped>
.blog-empty-section {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 400px;
  z-index: 2;

  .message {
    background-color: black;
    width: 280px;
    border-radius: 4px;
    padding: 10px;
    text-align: center;
  }

  .no-blog-posts {
    color: #444444;
    max-width: 500px;
    width: 100%;
    text-align: center;
    background-color: #efefef;
    padding: 10px;
    border-radius: 4px;
  }
}
</style>
