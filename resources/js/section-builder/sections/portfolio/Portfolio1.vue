<template>
  <div class="bz-section-container bz-sec--portfolio-1-root lg:tw-flex" :class="{ [breakPoint]: true }">
    <div class="portfolio-1_left-box lg:tw-w-[400px] tw-w-full tw-pl-4">
      <bz-background :setting="background">
        <div class="left-content">
          <div class="tw-mx-auto tw-w-fit tw-text-center">
            <bz-logo />
            <bz-text v-if="data.elements?.portfolioLink" v-model="data.elements.portfolioLink" />
          </div>

          <div class="menus">
            <template v-for="(page, index) in allPages">
              <div v-if="page.type !== 'new-page'" :key="index" class="link-item tw-ml-6">
                <bz-text :edit-mode="false" :model-value="page.name" />
              </div>
            </template>
          </div>

          <div class="tw-mt-auto tw-flex tw-flex-col tw-items-center tw-w-full">
            <div class="tw-w-fit tw-mx-auto">
              <bz-logo :title="false" />
            </div>
            <bz-social-icons />
          </div>
        </div>
      </bz-background>
    </div>
    <div v-if="items.length > 0" class="portfolio-1_right-box lg:tw-w-[calc(100%-400px)] tw-w-full">
      <template v-for="item in filteredItems" :key="item.id">
        <div class="portfolio-item">
          <bz-aspect-view>
            <img :src="getImgUrl(item)" class="portfolio-image" :alt="item.name" />
          </bz-aspect-view>
        </div>
      </template>
    </div>
    <div v-else-if="!items.length && isBuilder" class="portfolio-1_right-box lg:tw-w-[calc(100%-400px)] tw-w-full">
      <template v-for="_item in [1, 2, 3, 4]" :key="item">
        <div class="portfolio-item">
          <bz-aspect-view>
            <img src="https://picsum.photos/200" class="portfolio-image" alt="item.name" />
          </bz-aspect-view>
        </div>
      </template>
    </div>
    <template v-if="!items.length && !isBuilder">
      <div class="tw-text-gray-500 tw-text-xl tw-flex tw-items-center tw-justify-center">There are no portfolios yet...</div>
    </template>
  </div>
</template>

<script>
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzLogo from '../../components/section/BzLogo.vue'

import BzSocialIcons from '../../components/section/BzSocialIcons.vue'

import portfolioMixin from './portfolioMixin'

export default {
  name: 'Portfolio1',
  components: {
    BzSocialIcons,
    BzLogo,
    BzBackground,
    BzAspectView
  },
  mixins: [portfolioMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--portfolio-1-root {
  background-color: white;

  .portfolio-1_left-box {
    .left-content {
      display: flex;
      min-height: 100%;
      flex-direction: column;
      align-items: center;
      padding: 100px 0;

      .menus {
        margin-top: 50px;
        width: 200px;
        text-align: left;

        .link-item {
          font-weight: bold;
          text-transform: uppercase;
          font-size: 36px;
        }
      }
    }
  }

  .portfolio-1_right-box {
    padding: 20px;
    display: grid;
    grid-gap: 5px;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));

    .portfolio-item {
      .portfolio-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }
}
</style>
