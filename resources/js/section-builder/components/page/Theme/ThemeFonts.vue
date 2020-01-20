<template>
  <div>
    <p class="mt-3"><b>Font Type</b></p>
    <div class="btn-group w-100" role="group">
      <button type="button" class="btn bg-white p-2 w-100" :class="{ active: fontType === 'title' }" @click.prevent="fontType = 'title'">Title</button>
      <button type="button" class="btn bg-white p-2 w-100" :class="{ active: fontType === 'subtitle' }" @click.prevent="fontType = 'subtitle'">Subtitle</button>
      <button type="button" class="btn bg-white p-2 w-100" :class="{ active: fontType === 'description' }" @click.prevent="fontType = 'description'">Description</button>
    </div>
    <p class="mt-3"><b>Font Elements</b></p>
    <div class="row w-100 justify-content-center">
      <label for="bold" class="font-element" :class="{ active: bold }">
        <span class="bold">B</span>
        <input id="bold" v-model="bold" type="checkbox" hidden />
      </label>
      <label class="font-element">
        <span class="font-italic">I</span>
        <input type="checkbox" hidden />
      </label>
      <label class="font-element">
        <span class="underline">U</span>
        <input type="checkbox" hidden />
      </label>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../../mixins/builderMixin'

export default {
  name: 'ThemeFonts',
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      fontType: 'title'
    }
  },
  computed: {
    bold: {
      get() {
        return this.theme.fonts?.[this.fontType]?.bold
      },
      set(v) {
        _.set(this.theme, 'fonts.' + this.fontType + '.bold', v)
      }
    },
    theme: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.font-element {
  padding: 5px;
  margin: 5px;
}
.font-element.active {
  background-color: blue;
}
</style>
