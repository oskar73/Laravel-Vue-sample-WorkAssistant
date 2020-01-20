<template>
  <div class="bz-el-menu tw-items-center" :class="{'tw-flex tw-space-x-2': !vertical}">
    <template v-if="viewOnly">
      <template v-for="(page, index) of $store.state.themePreviewPages">
        <a v-if="showMenuItem(page)" :key="index" @click.stop.prevent="setActiveViewPage({ index, type: page.type })">
          <slot :page-name="page.name" :active="isActiveMenu(index, viewOnly)" />
        </a>
      </template>
    </template>
    <template v-else-if="edit">
      <template v-for="(page, index) of pages">
        <a v-if="showMenuItem(page)" :key="index" @click.stop.prevent="setActivePage({ index, type: page.type })">
          <slot :page-name="page.name" :active="isActiveMenu(page.url)" />
        </a>
      </template>
    </template>
    <template v-else>
      <template v-for="(page, index) of pages">
        <a v-if="showMenuItem(page)" :key="index" :href="pageUrl(page.url)">
          <slot :page-name="page.name" :active="isActiveMenu(page.url)" />
        </a>
      </template>
    </template>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import elementMixin from '../../mixins/elementMixin'
import builderMixin from '../../mixins/builderMixin'

export default defineComponent({
  name: 'BzNavBar',
  props: ['vertical'],
  mixins: [elementMixin, builderMixin],
  computed: {
    pages() {
      return this.allPages.map(page => {
        if (page.type == 'module') {
          let module = page.module_name

          if (!module) return {...page}

          if ((module).includes('blog')) {
            module = 'blog'
          }
          const url = this.template.module_url?.[module] ?? page.url
          return {...page, url}
        }
        return {...page}
      })
    }
  },
  methods: {
    showMenuItem(page) {
      if (page.type === 'new-page') {
        return false
      }

      if (page.type === 'module') {
        return this.modules.activeModules.includes(page.module_name)
      }

      return true
    }
  }
})
</script>

<style lang="scss" scoped>
.bz-el-menu {
  a {
    text-decoration: none;
    color: var(--bz-theme-text-color);
    display: block;
  }
}
</style>
