<template>
  <div class="w-100">
    <template v-if="pageHtml">
      <div class="w-100" v-html="pageHtml"></div>
    </template>
    <template v-else>
      <loader />
    </template>
  </div>
</template>

<script>
import axios from 'axios'
import Loader from '@/section-builder/components/EditPage.vue'
export default {
  name: 'ModuleView',
  components: { Loader },
  props: {
    name: {
      type: String,
      default: ''
    },
    url: {
      type: String,
      default: '/'
    }
  },
  data() {
    return {
      pageHtml: null
    }
  },
  created() {
    axios.get(this.url).then((res) => {
      if (res.status === 200) {
        this.pageHtml = res.data
      }
    })
  }
}
</script>

<style scoped></style>
