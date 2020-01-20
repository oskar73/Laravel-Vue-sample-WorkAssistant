<template>
  <div class="bz-tabs-root">
    <div class="bz-tabs-header">
      <button v-for="(tab, index) in tabs" :key="tab.label" ref="headerButton" v-ripple :class="{ 'bz-active': index === selectedIndex }" @click="selectTab(index)">
        {{ tab.label }}
      </button>
      <span class="bz-tabs-indicator" :style="indicatorStyle"></span>
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'BzTabs',
  data() {
    return {
      selectedIndex: 0, // the index of the selected tab,
      tabs: [], // all of the tabs
      indicatorWidth: 100,
      indicatorLeft: 20
    }
  },
  computed: {
    indicatorStyle() {
      return {
        width: this.indicatorWidth + 'px',
        left: this.indicatorLeft + 'px'
      }
    }
  },
  created() {
    console.log(this.$slots)
    // this.tabs = this.$children
  },
  mounted() {
    this.selectTab(0)
  },
  methods: {
    selectTab(i) {
      this.selectedIndex = i
      // loop over all the tabs
      this.tabs.forEach((tab, index) => {
        tab.isActive = index === i
      })

      this.$nextTick(() => {
        if (this.$refs.headerButton) {
          this.indicatorWidth = this.$refs.headerButton[i].offsetWidth
          this.indicatorLeft = this.$refs.headerButton[i].offsetLeft
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-tabs-root {
  display: flex;
  flex-direction: column;

  .bz-tabs-header {
    background-color: #f6f6f6 !important;
    padding: 0 20px;
    justify-content: flex-start;
    display: flex;
    position: relative;
    box-shadow: 0 0 0 0 rgb(0 0 0 / 20%), 0 0 0 0 rgb(0 0 0 / 14%), 0 0 0 0 rgb(0 0 0 / 12%);

    button {
      max-width: 264px;
      min-width: 72px;
      height: 48px;
      margin: 0;
      cursor: pointer;
      border-radius: 0;
      padding: 0 20px;
      display: inline-block;
      position: relative;
      overflow: hidden;
      outline: none;
      background: transparent;
      border: 0;
      font-family: inherit;
      line-height: normal;
      text-decoration: none;
      vertical-align: top;
      white-space: nowrap;

      &.bz-active {
        color: #448aff;
      }
    }

    .bz-tabs-indicator {
      height: 2px;
      position: absolute;
      bottom: 0;
      transform: translateZ(0);
      will-change: left, right;
      background-color: #448aff;
      transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
  }
}
</style>
