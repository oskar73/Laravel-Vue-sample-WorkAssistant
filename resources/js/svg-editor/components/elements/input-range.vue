<template>
  <vue-slider ref="slider" v-model="value" class="tw-w-full" :tooltips="false" :use-keyboard="true" :min="min" :max="max" :step="interval" @slide="handleSlide" />
</template>
<script>
import VueSlider from '@vueform/slider'

export default {
  name: 'InputRange',
  components: {
    VueSlider
  },
  props: {
    modelValue: {
      type: [Number, String, null],
      required: true
    },
    min: {
      type: Number,
      required: true
    },
    max: {
      type: Number,
      required: true
    },
    interval: {
      type: Number,
      default: 1
    }
  },
  computed: {
    value: {
      get() {
        return parseFloat(this.modelValue || 0)
      },
      set(value) {
        this.$emit('update:modelValue', parseFloat(value))
      }
    }
  },
  methods: {
    handleSlide(value) {
      this.$emit('update:modelValue', parseFloat(value))
    }
  }
}
</script>
<style lang="scss">
:root {
  --slider-connect-bg: rgba(58, 88, 249, 0.8);
}

@import '@vueform/slider/themes/default.css';
</style>
