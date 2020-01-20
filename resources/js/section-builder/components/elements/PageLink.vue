<template>
  <div class="bz-page-link-root">
    <div v-if="linkForm === 'text'" class="link-item-text" :class="sectionType" :style="{ color }">
      {{ name }}
    </div>
    <bz-button v-else class="link-item-button" :class="sectionType" :title="name" :custom-style="theme.link?.[sectionType]?.button" :edit-mode="false" />
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzButton from '../section/BzButton.vue'

export default {
  name: 'PageLink',
  components: { BzButton },
  mixins: [elementMixin],
  props: {
    active: {
      type: Boolean,
      default: false
    },
    name: {
      type: String,
      default: ''
    }
  },
  computed: {
    linkForm() {
      return this.theme.link?.[this.sectionType]?.type || 'text'
    }
  }
}
</script>

<style lang="scss">
.bz-page-link-root {
  .link-item-text {
    &.header {
      font-family: var(--bz-theme-link-header-font-family, 'Roboto');
      font-weight: var(--bz-theme-link-header-font-weight, normal);
      font-style: var(--bz-theme-link-header-font-style, normal);
      text-decoration: var(--bz-theme-link-header-text-decoration, inherit);
      color: var(--bz-theme-link-header-font-color, inherit);
      opacity: var(--bz-theme-link-header-font-opacity, 1);
      letter-spacing: var(--bz-theme-link-header-letter-spacing, normal);
      font-size: var(--bz-theme-link-header-font-size, inherit);
      text-wrap: nowrap;

      &:hover {
        text-decoration: var(--bz-theme-link-header-hover-text-decoration, none) !important;
        color: var(--bz-theme-link-header-hover-color, unset) !important;
      }
    }

    &.footer {
      font-family: var(--bz-theme-link-footer-font-family, 'Roboto');
      font-weight: var(--bz-theme-link-footer-font-weight, normal);
      font-style: var(--bz-theme-link-footer-font-style, normal);
      text-decoration: var(--bz-theme-link-footer-text-decoration, inherit);
      color: var(--bz-theme-link-footer-font-color, inherit);
      opacity: var(--bz-theme-link-footer-font-opacity, 1) !important;
      letter-spacing: var(--bz-theme-link-footer-letter-spacing, normal);
      font-size: var(--bz-theme-link-footer-font-size, inherit) !important;

      &:hover {
        text-decoration: var(--bz-theme-link-footer-hover-text-decoration, none) !important;
        color: var(--bz-theme-link-footer-hover-color, unset) !important;
      }
    }
  }
  .link-item-button {
  }
}
</style>
