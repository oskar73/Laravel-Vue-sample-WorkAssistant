<template>
  <div v-if="renderComponent" class="bz-el--button-group-root">
    <bz-button
      v-for="(button, index) of data"
      :key="index"
      v-model="data[index]"
      :index="index"
      :multiple="true"
      :last="index === data.length - 1"
      :start="index === 0"
      :bg-color="bgColor"
      @add="handleAddButton"
      @delete="handleDeleteButton"
      @swap="handleSwap"
    />
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzButton from './BzButton.vue'
import rerenderMixin from '../../mixins/rerenderMixin'
import eventBus from '@/public/eventBus'

export default {
  name: 'BzButtonGroup',
  components: {
    BzButton
  },
  mixins: [elementMixin, rerenderMixin],
  props: {
    vertical: {
      type: Boolean,
      default: false
    },
    bgColor: {
      type: [String],
      default: undefined
    }
  },
  watch: {
    data() {
      this.forceRerender()
    }
  },
  methods: {
    handleAddButton(index) {
      this.data.push({
        ...this.data[index],
        title: 'Button ' + (this.data.length + 1)
      })
      this.forceRerender()
      eventBus.$emit('button:added')
    },
    handleDeleteButton(index) {
      if (this.data.length > 1) {
        this.data.splice(index, 1)
      } else {
        this.activeSetting.elements.buttons = false
      }
      eventBus.$emit('button:removed')
    },
    handleSwap(index1, index2) {
      this.data.swap(index1, index2)
      this.forceRerender()
    }
  }
}
</script>
<style lang="scss">
.bz-el--button-group-root {
  width: max-content;
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-end;
}
</style>
