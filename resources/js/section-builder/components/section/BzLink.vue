<template>
  <div class="bz-el-link d-block w-100 h-100" :class="{ edit }">
    <template v-if="link.type === 'no-link' || edit">
      <slot />
    </template>
    <template v-else>
      <template v-if="link.type === 'web-address'">
        <a :href="link.webAddress" class="bz-h-100 bz-w-100" style="display: block" :target="link.target ? '_blank' : ''">
          <slot />
        </a>
      </template>
      <template v-if="link.type === 'page'">
        <a :href="pageUrl(link.page)" :target="link.target ? '_blank' : ''">
          <slot />
        </a>
      </template>
      <template v-if="link.type === 'email-address'">
        <a :href="`mailto:${link.email}?Subject=${link.subject || ''}`" class="bz-h-100 bz-w-100" style="display: block">
          <slot />
        </a>
      </template>
      <template v-if="link.type === 'phone-number'">
        <a :href="`tel:${link.phoneNumber}`" class="bz-h-100 bz-w-100" style="display: block">
          <slot />
        </a>
      </template>
    </template>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'

export default {
  name: 'BzLink',
  mixins: [elementMixin],
  props: {
    link: {
      type: Object,
      default: () => {
        return {
          type: 'no-link',
          value: 'javascript:void(0)'
        }
      }
    }
  }
}
</script>
<style scoped>
a {
  text-decoration: none !important;
}
</style>
