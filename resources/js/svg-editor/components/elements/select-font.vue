<template>
  <bz-select v-model="models.font" class="tw-w-full" search="value" :options="fonts" placeholder="Select Font" @input="changeFont">
    <template #selected="{ selected, placeholder }">
      <div v-if="selected" :style="{ 'font-family': selected.value }">{{ selected.value ?? placeholder }}</div>
      <div v-else>{{ placeholder }}</div>
    </template>
    <template #option="{ option }">
      <div :style="{ 'font-family': option.value }">{{ option.value }}</div>
    </template>
  </bz-select>
</template>

<script>
import axios from 'axios'
import BzSelect from '@/public/BzSelect.vue'

export default {
  name: 'SelectFont',
  components: {
    BzSelect
  },
  props: {
    modelValue: {
      type: [String, null],
      required: true
    }
  },
  data() {
    return {
      models: {
        font: {
          value: null
        }
      },
      fonts: []
    }
  },
  watch: {
    modelValue: function (font) {
      this.models.font = {
        name: font,
        value: font
      }
    }
  },
  mounted() {
    this.loadFonts()
  },

  methods: {
    loadFonts() {
      const self = this
      axios.get(this.route('fonts.get')).then(function (response) {
        self.fonts = response.data.map((font) => {
          return {
            label: font.name,
            value: font.name
          }
        })
      })
    },
    changeFont(font) {
      this.$emit('update:modelValue', font.value)
    }
  }
}
</script>
<style lang="scss">
@import '@vueform/multiselect/themes/default.css';
</style>
