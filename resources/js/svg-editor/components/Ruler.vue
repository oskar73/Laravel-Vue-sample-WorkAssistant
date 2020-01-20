<template>
  <div class="tw-w-4 tw-h-4 tw-bg-primary tw-absolute tw-top-0 tw-left-0 tw-flex tw-justify-center tw-items-center tw-z-40 tw-text-white"></div>
  <div class="tw-absolute tw-top-0 tw-left-0 tw-h-4 tw-right-0 z-20">
    <div class="tw-w-full tw-flex tw-h-full tw-bg-green-50 tw-border-b tw-items-center tw-justify-center tw-relative">
      <div v-if="gridMode" class="tw-absolute tw-left-0 tw-h-full tw-flex tw-flex-row-reverse" :style="{ width: `calc((100% - ${width * zoom}px) / 2 + ${translateX}px)` }">
        <template v-for="(_, index) in Array(50)" :key="index">
          <div
            class="tw-shrink-0 tw-border-l tw-border-l-primary tw-top-full tw-h-[100vh] tw-opacity-30 absolute"
            :style="{ width: delta * zoom + 'px', right: delta * zoom * index + 'px' }"
          ></div>
        </template>
      </div>
      <div
        class="tw-border-r tw-h-full tw-flex after:tw-content-[var(--width)] after:tw-flex after:tw-bg-green-50 after:tw-items-center after:tw-h-full after:tw-absolute tw-relative after:tw-left-[calc(100%+2px)] after:tw-w-full after:tw-pl-1 after:tw-text-gray-600"
        :style="rulerHStyle"
        :class="{
          'before:tw-content-[\'\'] before:tw-h-[100vw] before:tw-w-px before:tw-bg-primary before:tw-absolute before:tw-opacity-30 before:tw-right-[-1px] before:tw-top-full':
            gridMode
        }"
      >
        <template v-for="coordinate in coordinatesX()" :key="coordinate">
          <div
            class="tw-border-l tw-h-full tw-flex tw-flex-shrink-0 tw-items-end tw-justify-evenly tw-pl-px tw-text-gray-500 tw-relative"
            :style="{ width: delta * zoom - 0.5 + 'px' }"
            :class="{
              'after:tw-content-[\'\'] after:tw-h-[100vh] after:tw-w-px after:tw-bg-primary after:tw-opacity-30 after:tw-absolute after:tw-left-[-1.5px] after:tw-top-full':
                gridMode
            }"
          >
            <div class="tw-absolute tw-bottom-px tw-left-px">{{ coordinate }}</div>
            <div v-if="delta > deltaOptions[0]" class="tw-h-1.5 tw-w-px tw-bg-gray-400"></div>
            <div v-if="delta > deltaOptions[3]" class="tw-h-1.5 tw-w-px tw-bg-gray-400"></div>
            <div v-if="delta > deltaOptions[6]" class="tw-h-1.5 tw-w-px tw-bg-gray-400"></div>
          </div>
        </template>
      </div>
      <div v-if="gridMode" class="tw-absolute tw-right-0 tw-h-full tw-flex" :style="{ width: `calc((100% - ${width * zoom}px) / 2 - ${translateX}px)` }">
        <template v-for="(_, index) in Array(50)" :key="index">
          <div
            class="tw-shrink-0 tw-border-r tw-border-r-primary tw-top-full tw-h-[100vh] tw-opacity-30 absolute"
            :style="{ width: delta * zoom + 'px', left: delta * zoom * index + 'px' }"
          ></div>
        </template>
      </div>
    </div>
  </div>
  <div class="tw-absolute tw-top-0 tw-left-0 tw-w-4 tw-bottom-0 tw-z-20">
    <div class="tw-w-full tw-h-full tw-bg-green-50 tw-border-r tw-flex tw-items-center tw-relative">
      <div v-if="gridMode" class="tw-absolute tw-top-0 tw-w-full tw-flex tw-flex-col" :style="{ height: `calc((100% - ${height * zoom}px) / 2 + ${translateY}px)` }">
        <template v-for="(_, index) in Array(50)" :key="index">
          <div
            class="tw-shrink-0 tw-border-t tw-border-t-primary tw-left-full tw-w-[100vw] tw-opacity-30 tw-absolute"
            :style="{ height: delta * zoom + 'px', bottom: delta * zoom * index + 'px' }"
          ></div>
        </template>
      </div>
      <div
        class="tw-border-b tw-w-full tw-flex tw-flex-col after:tw-content-[var(--height)] after:tw-flex after:tw-bg-green-50 after:tw-items-center after:tw-w-full after:tw-absolute tw-relative after:tw-top-[calc(100%+2px)] after:tw-h-full after:tw-pt-1 after:tw-text-gray-600 after:tw-vertical-lr"
        :style="rulerVStyle"
        :class="{
          'before:tw-content-[\'\'] before:tw-w-[100vw] before:tw-h-px before:tw-bg-primary before:tw-absolute before:tw-opacity-30 before:tw-bottom-[-1px] before:tw-left-full':
            gridMode
        }"
      >
        <template v-for="coordinate in coordinatesY()" :key="coordinate">
          <div
            class="tw-border-t tw-w-full tw-flex tw-flex-shrink-0 tw-flex-col tw-justify-evenly tw-items-end tw-pt-px tw-text-gray-500 tw-relative"
            :style="{ height: delta * zoom - 0.5 + 'px' }"
            :class="{
              'after:tw-content-[\'\'] after:tw-w-[100vw] after:tw-h-px after:tw-bg-primary after:tw-opacity-30 after:tw-absolute after:tw-top-[-1px] after:tw-left-full': gridMode
            }"
          >
            <div class="tw-absolute tw-top-1 tw-left-px tw-vertical-lr">{{ coordinate }}</div>
            <div v-if="delta > deltaOptions[0]" class="tw-w-1.5 tw-h-px tw-bg-gray-400"></div>
            <div v-if="delta > deltaOptions[3]" class="tw-w-1.5 tw-h-px tw-bg-gray-400"></div>
            <div v-if="delta > deltaOptions[6]" class="tw-w-1.5 tw-h-px tw-bg-gray-400"></div>
          </div>
        </template>
      </div>
      <div v-if="gridMode" class="tw-absolute tw-bottom-0 tw-w-full tw-flex tw-flex-col" :style="{ height: `calc((100% - ${height * zoom}px) / 2 - ${translateY}px)` }">
        <template v-for="(_, index) in Array(50)" :key="index">
          <div
            class="tw-shrink-0 tw-border-b tw-border-b-primary tw-left-full tw-w-[100vw] tw-opacity-30 tw-absolute"
            :style="{ height: delta * zoom + 'px', top: delta * zoom * index + 'px' }"
          ></div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Ruler',
  props: {
    width: {
      type: Number,
      required: true
    },
    height: {
      type: Number,
      required: true
    },
    zoom: {
      type: Number,
      default: 1
    },
    translateX: {
      type: Number,
      default: 0
    },
    translateY: {
      type: Number,
      default: 0
    },
    gridMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      delta: 1,
      deltaOptions: [1, 2, 4, 5, 10, 15, 20, 25, 50, 100, 200, 400, 500, 1000, 1500, 2000, 2500, 5000, 10000, 15000, 20000, 25000, 50000, 100000]
    }
  },
  computed: {
    rulerHStyle() {
      return {
        width: this.width * this.zoom + 1 + 'px',
        '--width': `"${this.width}px"`,
        fontSize: '11px',
        lineHeight: '10px',
        transform: `translate(${this.translateX}px)`
      }
    },
    rulerVStyle() {
      return {
        height: this.height * this.zoom + 'px',
        '--height': `"${this.height}px"`,
        fontSize: '11px',
        lineHeight: '10px',
        transform: `translate(0px , ${this.translateY}px)`
      }
    }
  },
  methods: {
    getDelta() {
      let length = 0
      if (this.width >= this.height) {
        length = this.width
      } else {
        length = this.height
      }

      const lengthPx = length * this.zoom
      const count = Math.round(lengthPx / 100)

      const delta = length / count
      const index = this.deltaOptions.findIndex((item) => item > delta)
      return this.deltaOptions[Math.min(this.deltaOptions.length - 1, index)]
    },
    coordinatesX() {
      this.delta = this.getDelta()
      const coords = []
      let cx = 0

      while (cx < this.width) {
        coords.push(cx)
        cx += this.delta
      }

      return coords
    },
    coordinatesY() {
      this.delta = this.getDelta()
      const coords = []
      let cx = 0

      while (cx < this.height) {
        coords.push(cx)
        cx += this.delta
      }

      return coords
    }
  }
}
</script>

<style lang="scss" scoped></style>
