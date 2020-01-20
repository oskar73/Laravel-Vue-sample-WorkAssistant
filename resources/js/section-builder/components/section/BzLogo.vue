<template>
  <div class="bz-el-logo-root tw-flex tw-items-center">
    <a class="tw-flex tw-items-center tw-cursor-pointer" @click="goToHome()">
      <bz-title v-if="title" v-model="template.data.name" :mb="0" />
      <template v-else>
        <img class="tw-w-16 md:tw-w-24" :src="template.data.logo.url" alt="Logo" />
      </template>
    </a>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzTitle from './BzTitle.vue'
import elementMixin from '../../mixins/elementMixin'
export default defineComponent({
  name: 'BzLogo',
  components: { BzTitle },
  mixins: [elementMixin],
  props: {
    title: {
      type: Boolean,
      default: true
    },
    logoSize: {
      type: [Object, Array],
      default: () => {
        return {
          width: undefined,
          height: undefined
        }
      }
    },
    resizeDirection: {
      type: String,
      default: 'right-bottom'
    }
  },
  computed: {
    _logoSize: {
      get() {
        return this.logoSize
      },
      set(value) {
        this.$emit('changeSize', value)
      }
    },
    template() {
      return this.$store.state.template
    }
  },
  methods: {
    goToHome() {
      if (this.isBuilder) return
      location.href = '/'
    }
  }
})
</script>

<style lang="scss" scoped>
.bz-el-logo-root {
  width: 100%;

  a {
    color: unset;
  }

  a:hover {
    text-decoration: none;
    color: unset;
  }

  img {
    object-fit: contain;
  }
}
</style>
