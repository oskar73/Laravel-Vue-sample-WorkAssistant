<script>
import sectionMixin from '../../mixins/sectionMixin'

export default {
  mixins: [sectionMixin],
  data() {
    return {
      aspectRatio: 1
    }
  },
  computed: {
    showEmptySection() {
      const blogModule = this.$store.state.modules.blog
      if (this.preview || this.isTemplate) {
        return false
      }

      if (blogModule) {
        return blogModule.posts.length === 0
      }

      return true
    },
    blogPosts: {
      get() {
        const posts = []
        const blogModule = this.$store.state.modules.blog
        if (blogModule && blogModule.posts.length > 0) {
          let blogCount = parseInt(this.setting.blog.blogCount)
          let allPosts = blogModule.posts

          if (this.setting.blog.category) {
            allPosts = allPosts.filter((item) => {
              return item.category_id === this.setting.blog.category
            })
          }

          if (isNaN(blogCount) || typeof blogCount !== 'number' || blogCount < 1) {
            blogCount = 1
          }

          for (let i = 0; i < blogCount; i++) {
            const blogItem = allPosts[i]
            if (blogItem) {
              let blogDescription = this.extractContent(blogItem.body)

              if (blogDescription.length > this.setting.blog.descriptionLength) {
                blogDescription = blogDescription.substring(0, this.setting.blog.descriptionLength) + ' ...'
              }

              let post_url = '#'
              let post_img = blogItem.image.replace('\\', '/')
              if (this.$store.state.modules.blog.page) {
                post_url = this.$store.state.modules.blog.page.url + '/' + this.$store.state.modules.blog.detail + '/' + blogItem.slug
              }
              if (this.isBuilder) {
                post_img = post_img.replace('media', `${this.$store.state.template.id}/media`)
              }

              posts.push({
                image: {
                  src: post_img
                },
                visible_date: blogItem.visible_date,
                title: blogItem.title,
                description: blogDescription,
                slug: blogItem.slug,
                url: post_url
              })
            } else {
              if (this.edit) {
                posts.push(null)
              }
            }
          }
        }

        if (((this.preview || posts.length === 0) && this.isBuilder) || this.isTemplate) {
          return this.data.items
        }

        return posts
      },
      set(newValue) {
        if (this.preview || !this.isWebsite) {
          this.data.items = newValue
        } else {
          // this.$store.state.modules.blog.posts = newValue
        }
      }
    },
    postBlogUrl() {
      return this.template.webUrl + '/admin/blog/post/create'
    },
    imageStyle() {
      if (this.setting.layouts.aspectRatio === 'circle') {
        return {
          borderRadius: '100000px'
        }
      }
      return {}
    }
  },
  watch: {
    setting: {
      deep: true,
      immediate: true,
      handler(value) {
        if (value.layouts?.aspectRatio) {
          switch (value.layouts.aspectRatio) {
            case 'landscape': {
              this.aspectRatio = 2 / 3
              break
            }
            case 'portrait': {
              this.aspectRatio = 4 / 3
              break
            }
            case 'square': {
              this.aspectRatio = 1
              break
            }
            default: {
              this.aspectRatio = 1
            }
          }
        }
      }
    }
  },
  methods: {
    extractContent(s) {
      const html = document.createElement('div')
      html.innerHTML = s
      return html.textContent || html.innerText
    },
    goToPage() {
      if (this.isBuilder) return

      console.log(this.allPages)
      // window.location.href = this.allPages.find(({ module_name }) => module_name == 'product')?.url || '/blog'
    },
    handleAddItem() {
      window.open(this.domain + '/admin/blog/post', '_blank')
    }
  }
}
</script>
