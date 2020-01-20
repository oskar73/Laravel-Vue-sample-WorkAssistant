<template>
  <div class="bz-el-video-root" :class="{ [breakPoint]: true }">
    <iframe
      :src="`https://www.youtube.com/embed/${videoId}?autoplay=${autoplay}&loop=${loop}&mute=${mute}&controls=${controls}&playlist=${videoId}&showinfo=0&enablejsapi=1`"
      allowfullscreen
      allow="autoplay"
      class="w-100"
    ></iframe>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import { mapMutations } from 'vuex'
import { cloneDeep, merge } from 'lodash'

export default {
  name: 'BzVideo',
  mixins: [elementMixin],
  props: {
    modelValue: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      editing: false,
      youtubeURL: '',
      breakPoint: 'bz-xl',
      videoData: {}
    }
  },
  computed: {
    videoId() {
      let videoId = ''
      // eslint-disable-next-line
      const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^\#\&\?]*).*/
      const match = this.videoData.source.match(regExp)
      if (match && match[7].length === 11) {
        videoId = match[7]
      }
      return videoId
    },
    autoplay() {
      if (this.videoData.autoplay) {
        return 1
      }
      return 0
    },
    loop() {
      if (this.videoData.loop) {
        return 1
      }
      return 0
    },
    mute() {
      if (this.videoData.mute) {
        return 1
      }
      return 0
    },
    controls() {
      if (this.videoData.controls) {
        return 1
      }
      return 0
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      deep: true,
      handler(val) {
        this.videoData = cloneDeep(
          merge(
            {
              source: 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
              autoPlay: false,
              loop: false,
              mute: false,
              controls: true
            },
            val ?? {}
          )
        )
      }
    }
  },
  methods: {
    openVideoModal() {
      this.openYoutubeVideo({
        onChange: (value) => {
          this.$emit('update:modelValue', value)
        },
        value: cloneDeep(this.modelValue)
      })
    },
    ...mapMutations(['openYoutubeVideo'])
  }
}
</script>

<style lang="scss">
.bz-el-video-root {
  height: 100%;
  background-color: black;
  iframe {
    width: 100%;
    height: 100%;
  }
}
</style>
