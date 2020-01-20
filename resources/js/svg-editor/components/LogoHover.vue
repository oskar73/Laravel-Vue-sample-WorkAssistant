<template>
  <div v-if="previewUrl" class="logo-hover-wrapper">
    <div v-for="color in colors" class="logo-hover-container">
      <span class="logo-hover-color" :style="{ background: color }"></span>
    </div>
  </div>
</template>

<script>
import Vibrant from 'node-vibrant'

export default {
  name: 'LogoHover',

  props: {
    previewUrl: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      previews: {},
      colors: []
    }
  },

  mounted() {
    this.setColors()
  },

  methods: {
    setColors() {
      setTimeout(() => {
        Vibrant.from(this.previewUrl)
          .getSwatches()
          .then((swatches) => {
            // Set colors
            for (const swatch in swatches) {
              if (swatches.hasOwnProperty(swatch) && swatches[swatch]) {
                this.colors.push(swatches[swatch].getHex())
              }
            }
          })
      })
    }
  }
}
</script>

<style lang="scss">
.logoItemContainer {
  .logo-hover-wrapper {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .hover_preview_img_a .logo-hover-wrapper {
    position: absolute;
    right: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .logo-hover-container {
    display: flex;
    height: 100%;

    .logo-hover-color {
      align-items: center;
      justify-content: center;
      min-width: 30px;
      display: flex;
    }
  }
  .tippy-content {
    height: 100%;
    padding: 0;
  }

  .tippy-tooltip {
    padding: 0;
    border-radius: 0 5px 5px 0;
    overflow: hidden;
  }
  .tippy-popper {
    bottom: 0;
    display: flex;
  }
}
</style>
