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
      const marketplaceModule = this.$store.state.modules.marketplace
      if (this.preview || this.isTemplate) {
        return false
      }

      if (marketplaceModule) {
        return marketplaceModule.posts.length === 0
      }

      return true
    },
    marketplacePosts: {
      get() {
        const posts = []
        const marketplaceModule = this.$store.state.modules.marketplace
        if (marketplaceModule && marketplaceModule.posts.length > 0) {
          let marketplaceCount = parseInt(this.setting.marketplace.marketplaceCount)
          let allPosts = marketplaceModule.posts

          if (this.setting.marketplace.category) {
            allPosts = allPosts.filter((item) => {
              return item.category_id === this.setting.marketplace.category
            })
          }

          if (isNaN(marketplaceCount) || typeof marketplaceCount !== 'number' || marketplaceCount < 1) {
            marketplaceCount = 1
          }

          for (let i = 0; i < marketplaceCount; i++) {
            const marketplaceItems = allPosts[i]
            if (marketplaceItems) {
              let marketplaceDescription = this.extractContent(marketplaceItems.body)

              if (marketplaceDescription.length > this.setting.marketplace.descriptionLength) {
                marketplaceDescription = marketplaceDescription.substring(0, this.setting.marketplace.descriptionLength) + ' ...'
              }

              let post_url = '#'
              let post_img = marketplaceItems.image.replace('\\', '/')
              if (this.$store.state.modules.marketplace.page) {
                post_url = this.$store.state.modules.marketplace.page.url + '/' + this.$store.state.modules.marketplace.detail + '/' + marketplaceItems.slug
              }
              if (this.isBuilder) {
                post_img = post_img.replace('media', `${this.$store.state.template.id}/media`)
              }

              posts.push({
                image: {
                  src: post_img
                },
                visible_date: marketplaceItems.visible_date,
                title: marketplaceItems.title,
                description: marketplaceDescription,
                slug: marketplaceItems.slug,
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
          // this.$store.state.modules.marketplace.posts = newValue
        }
      }
    },
    postBlogUrl() {
      return this.template.webUrl + '/admin/marketplace/post/create'
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
      window.open(this.domain + '/admin/marketplace/post', '_blank')
    }
  }
}
</script>
