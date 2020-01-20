<template>
  <div class="theme-mode">
    <div :class="{ active: colorMode === 'light' }">
      <button class="light-mode" @click.prevent="colorMode = 'light'">Light</button>
    </div>
    <div :class="{ active: colorMode === 'dark' }">
      <button class="dark-mode" @click.prevent="colorMode = 'dark'">Dark</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ColorMode',
  props: {
    modelValue: {
      type: String,
      default: 'light'
    }
  },
  computed: {
    colorMode: {
      get() {
        return this.modelValue
      },
      set(v) {
        this.$emit('update:modelValue', v)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.theme-mode {
  display: flex;
  align-items: center;
  justify-content: space-between;

  div {
    padding: 1px;
    border-radius: 5px;
    width: 48%;
    height: 38px;

    &.active {
      border: solid 2px #0076df;
    }

    button {
      width: 100%;
      height: 100%;
      border-radius: 4px;
      border: solid 1px #8080803f;
    }

    .light-mode {
      background-color: #eee;
      color: #111;
    }

    .dark-mode {
      background-color: #111;
      color: #eee;
    }
  }
}
</style>
